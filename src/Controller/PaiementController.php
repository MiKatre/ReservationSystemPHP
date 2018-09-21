<?php

namespace App\Controller;

use App\Entity\Order;
use App\Events;
use App\EventSubscriber\OrderPlacedEvent;
use App\Utils\ErrorControl;
use App\Utils\PriceControl;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class PaiementController extends AbstractController
{
    /**
     * @Route("/api/pay", name="api_pay", methods={"POST"})
     */
    public function pay(Request $request, SessionInterface $session, \Swift_Mailer $mailer, PriceControl $price, ErrorControl $errorControl, EventDispatcherInterface $eventDispatcher){

        if ($request->getContentType() != 'json' || !$request->getContent())
            return $errorControl->error(500, 'Mauvais format de données. JSON attendu.');

        $data = json_decode($request->getContent());

        if (!isset($data->token) || empty($data->token))
            return $errorControl->error(500, 'No token received');

        if (!isset($data->token->id) || empty($data->token->id))
            return $errorControl->error(500, 'Payload received without id');

        $token = $data->token->id;

        $entityManager = $this->getDoctrine()->getManager();
        $order = $entityManager
            ->getRepository(Order::class)
            ->find($session->get('order')->getId());

        $tickets = $order->getTickets();

        $amount = $price->getTotalTTC($tickets);

        if ($amount <= 0){
            return $errorControl->error(500, 'Anormal amount');
        }

        \Stripe\Stripe::setApiKey(getenv('stripe_secret_key'));

        $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => 'eur',
            'source' => $token,
            'receipt_email' => $session->get('order')->getEmail(),
        ]);

        // $charge->paid === true
        // $charge->status === 'succeeded'
        // $charge->outcome->seller_message === 'Payment complete.'
        if($charge->paid === true) {
            $order->setIsPaid(true);
            $entityManager->persist($order);
            $entityManager->flush();
        }

        $event = new GenericEvent();
        $eventDispatcher->dispatch(Events::ORDER_PLACED, $event);

        return $this->json(array(
            'paid' => $charge->paid,
            'email' => $charge->receipt_email,
            'message' => "Commande passée avec succès"
        ));
    }
}