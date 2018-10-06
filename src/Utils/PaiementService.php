<?php

namespace App\Utils;

use App\Entity\Date;
use App\Entity\Order;
use App\Events;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PaiementService 
{
  private $em;
  private $session;
  private $errorControl;
  private $price;
  private $eventDispatcher;

  public function __construct(EntityManagerInterface $em, SessionInterface $session, PriceControl $price, ErrorControl $errorControl, EventDispatcherInterface $eventDispatcher) {
    $this->em = $em;
    $this->session = $session;
    $this->price = $price;
    $this->errorControl = $errorControl;
    $this->eventDispatcher = $eventDispatcher;
  }


 /**
  * @param $data
  * @return object
  * @throws \Exception
  */
  public function pay($data)
  {
    $token = $data->token->id;

    $entityManager = $this->em;
    $session = $this->session;


    $order = $entityManager
        ->getRepository(Order::class)
        ->find($this->session->get('order')->getId());

    $tickets = $order->getTickets();

    $amount = $this->price->getTotalTTC($tickets);

    if ($amount <= 0)
        return (object) ['error' => true, 'status' => 500, 'message' => 'Montant incorrect.'];

    \Stripe\Stripe::setApiKey(getenv('stripe_secret_key'));

    $charge = \Stripe\Charge::create([
        'amount' => $amount,
        'currency' => 'eur',
        'source' => $token,
        'receipt_email' => $session->get('order')->getEmail(),
    ]);

    if($charge->paid !== true)
        return (object) ['error' => true, 'status' => 500, 'message' => 'Erreur lors du paiement.'];

    // Increment remaining tickets
    $remainingTickets = $entityManager->getRepository(Date::class)->findOneBy(
        ['date' => $order->getDate()]
    );

    if(empty($remainingTickets)) {
        $remainingTickets = new Date();
        $remainingTickets->setDate($order->getDate());
        $remainingTickets->setNbOfTickets(count($order->getTickets()));
    } else {
        $remainingTickets->setNbOfTickets($remainingTickets->getNbOfTickets() + count($order->getTickets()));
    }

    $order->setIsPaid(true);
    $entityManager->persist($order);
    $entityManager->persist($remainingTickets);
    $entityManager->flush();

    $event = new GenericEvent();
    $this->eventDispatcher->dispatch(Events::ORDER_PLACED, $event);

    return (object) ['charge' => $charge, 'error' => false];
  }
}