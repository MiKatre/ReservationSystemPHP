<?php

namespace App\Utils;

use App\Entity\Date;
use App\Entity\Order;
use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class TicketService 
{
    private $em;
    private $session;
    private $price;
    private $errorControl;

    public function __construct(EntityManagerInterface $em, SessionInterface $session, PriceControl $price, ErrorControl $errorControl) {
        $this->em = $em;
        $this->session = $session;
        $this->price = $price;
        $this->errorControl = $errorControl;
    }

    /**
     * @param $data
     * @return Ticket
     */
    private function hydrate($data){
        
        $dateOfBirth = new \DateTime($data->dateOfBirth);

        $ticket = new Ticket();
        $ticket->setFirstName($data->firstName);
        $ticket->setLastName($data->lastName);
        $ticket->setDateOfBirth($dateOfBirth);
        $ticket->setDiscount($data->discount);
        $ticket->setPrice($this->price->getPriceHT($dateOfBirth, $data->discount, $data->isFullDay )->price);
        $ticket->setIsFullDay($data->isFullDay);
        $ticket->setCountry($data->country);

        return $ticket;
    }

    /**
     * @param $data
     * @return Order|null|object
     */
    public function addTicketToOrder($data)
    {
        $order = $this->em
            ->getRepository(Order::class)
            ->find($this->session->get('order')->getId());

        $ticket = $this->hydrate($data);

        $order->addTicket($ticket);

        $this->em->persist($order);
        $this->em->flush();

        $this->session->set('tickets', $order->getTickets());

        return $order;
    }

    /**
     * @param $data
     * @return bool|\Symfony\Component\HttpFoundation\Response
     */
    public function remove($data)
    {
        $order = $this->em
            ->getRepository(Order::class)
            ->find($this->session->get('order')->getId());

        $ticket = null;

        foreach ($order->getTickets() as $singleTicket){
            if ($singleTicket->getId() === $data->id) {
                $ticket = $singleTicket;
            }
        }

        if ($ticket === null)
            return $this->errorControl->error(500, 'Ticket introuvable.');

        $order->removeTicket($ticket);
        $this->em->persist($order);
        $this->em->flush();

        $this->session->set('tickets', $order->getTickets());

        return true;
    }

    /**
     * @param $date
     * @return object
     */
    public function remaining($date){
        $dateObject = new \DateTime($date);
        $normalizedDate = $dateObject->format('d/m/Y');

        $entity = $this->em->getRepository(Date::class)->findOneBy(
            ['date' => $dateObject]
        );

        $remaining = Date::MAX_TICKETS_PER_DAY;
        if(!empty($entity)) {
            $remaining = Date::MAX_TICKETS_PER_DAY - $entity->getNbOfTickets();
        }

        $response = (object) ['remaining' => $remaining, 'date' => $normalizedDate];
        return $response;
    }
}