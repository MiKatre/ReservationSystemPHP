<?php
/**
 * Created by PhpStorm.
 * User: MDP
 * Date: 09/08/2018
 * Time: 20:42
 */

namespace App\Controller;


use App\Entity\Order;
use App\Entity\Price;
use App\Entity\Ticket;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homepage() {

        $entityManager = $this->getDoctrine()->getManager();

        $price = $entityManager
            ->getRepository('App:Price')
            ->find(1);

//        $price = new Price();
//        $price->setName('kid');
//        $price->setPrice(300);

        $order = new Order();
        $order->setFirstName('Pauline');
        $order->setLastName('Alg');
        $order->setEmail('pauline.algebra@organisation.org');
        $order->setReservationCode('X06h71k');

        $ticket = new Ticket();
        $ticket->setFirstName('Pauline');
        $ticket->setLastName('Algebra');
        $ticket->setDateOfBirth(new \DateTime("10-10-1990"));
        $ticket->setDiscount(true);
        $ticket->setPrice($price->getPrice());
//        $ticket->setOrderRelation($order);

        $order->addTicket($ticket);

        $entityManager->persist($order);



        $entityManager->flush();

        return new Response('Nouvelle commande créée au nom de ' . $order->getFirstName() . ' ' .  $order->getLastName());
    }

    /**
     * @Route("/commande/{id}")
     */
    public function showOrder($id){
        $repo = $this->getDoctrine()->getManager()->getRepository('App:Order');

        $order = $repo->find(1);

        return new Response("La commande " . $order->getReservationCode() . " a été passée au nom de " . $order->getFirstName() . " " .  $order->getLastName());
    }
}