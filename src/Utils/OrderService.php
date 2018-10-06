<?php

namespace App\Utils;

use App\Entity\Date;
use App\Entity\Order;
use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class OrderService 
{
  private $em;
  private $session;
  private $price;
  private $errorControl;
  private $validator;

  public function __construct(EntityManagerInterface $em, SessionInterface $session, PriceControl $price, ErrorControl $errorControl, ValidatorInterface $validator) {
    $this->em = $em;
    $this->session = $session;
    $this->price = $price;
    $this->validator = $validator;
    $this->errorControl = $errorControl;
  }

 /**
  * @param Order $order
  * @param $data
  * @throws \Exception
  */
  private function hydrate(Order $order, $data){
    $order->setFirstName($data->firstName);
    $order->setLastName($data->lastName);
    $order->setEmail($data->email);

    $order->setDate(new \DateTime($data->date));

    // If we are creating a new order
    if (!$this->session->has('order')) {
      // Generate a random reservation code.
      $random = bin2hex(random_bytes(4));
      $order->setReservationCode($random);
    }
  }

 /**
  * @param $data
  * @return object
  * @throws \Exception
  */
  public function create($data)
  {
    $order = $this->session->has('order') ?
      $this->em
        ->getRepository(Order::class)
        ->find($this->session->get('order')
          ->getId() )
      : new Order();

    $this->hydrate($order, $data);

    $errors = $this->validator->validate($order);

    if(count($errors) > 0) {
        $errorsArray = [];

        foreach ($errors as $violation) {
            $errorsArray[] = $violation->getMessage();
        }
        $response = (object) ['error' => true, 'errorsArray' => $errorsArray];
        return $response;
    }

    $this->em->persist($order);
    $this->em->flush();
    $message = $this->session->has('order') ? 'Commande mise à jour' : 'Commande créée' ;
    $this->session->set('order', $order);

    $response = (object) ['error' => false, 'message' => $message];
    return $response;
  }
}