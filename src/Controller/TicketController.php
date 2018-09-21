<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Ticket;
use App\Entity\Date;
use App\Utils\ErrorControl;
use App\Utils\PriceControl;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    /**
     * @Route("/api/add_ticket", name="api_add_ticket", methods={"POST"})
     */
    public function addTicket(Request $request, ValidatorInterface $validator, SessionInterface $session, PriceControl $price, ErrorControl $errorControl){
        // Return errorMessage or success message with the id

        if ($request->getContentType() != 'json' || !$request->getContent())
            return $errorControl->error(500, 'Mauvais format de données. JSON attendu.');

        $data = json_decode($request->getContent());

        $entityManager = $this->getDoctrine()->getManager();

        $order = $entityManager
            ->getRepository(Order::class)
            ->find($session->get('order')->getId());

        $dateOfBirth = new \DateTime($data->dateOfBirth);

        $ticket = new Ticket();
        $ticket->setFirstName($data->firstName);
        $ticket->setLastName($data->lastName);
        $ticket->setDateOfBirth($dateOfBirth);
        $ticket->setDiscount($data->discount);
        $ticket->setPrice($price->getPriceHT($dateOfBirth, $data->discount, $data->isFullDay )->price);
        $ticket->setIsFullDay($data->isFullDay);
        $ticket->setCountry($data->country);

        $order->addTicket($ticket);

        $remainingTickets = $entityManager->getRepository(Date::class)->findOneBy(
            ['date' => $order->getDate()]
        );

        if(empty($remainingTickets)) {
            $remainingTickets = new Date();
            $remainingTickets->setDate($order->getDate());
            $remainingTickets->setNbOfTickets(1);
        } else {
            $remainingTickets->setNbOfTickets($remainingTickets->getNbOfTickets() + 1);
        }

        $entityManager->persist($order);
        $entityManager->persist($remainingTickets);
        $entityManager->flush();

        $session->set('tickets', $order->getTickets());

        $tickets = [];

        foreach ($order->getTickets() as $singleTicket){
            $dateOfBirth = $singleTicket->getDateOfBirth();
            $object = (object) [
                'id' => $singleTicket->getId(),
                'firstName' => $singleTicket->getFirstName(),
                'lastName' => $singleTicket->getLastName(),
                'dateOfBirth' => $dateOfBirth,
                'price' => $price->getPriceHT($dateOfBirth, $singleTicket->getDiscount(), $singleTicket->getIsFullDay())->price,
                'priceName' => $price->getPriceHT($dateOfBirth, $singleTicket->getDiscount(), $singleTicket->getIsFullDay())->name,
                'isFullDay' => $singleTicket->getIsFullDay(),
            ];
            $tickets[] = $object;
        }

        return $this->json(array(
            'success' => true,
            'tickets' => $tickets,
            'totalHT' => $price->getTotalHT($order->getTickets()),
            'totalTTC' => $price->getTotalTTC($order->getTickets()),
            'TVA' => PriceControl::TVA,
            'message' => 'Billet ajouté',
        ));
    }


    /**
     * @Route("/api/remove_ticket", name="api_remove_ticket", methods={"DELETE"})
     */
    public function removeTicket(Request $request, SessionInterface $session, ErrorControl $errorControl) {

        if ($request->getContentType() != 'json' || !$request->getContent()) {
            return $errorControl->error(500, 'Mauvais format de données. JSON attendu.');
        }

        $data = json_decode($request->getContent());

        $entityManager = $this->getDoctrine()->getManager();

        $order = $entityManager
            ->getRepository(Order::class)
            ->find($session->get('order')->getId());

        $ticket = null;

        foreach ($order->getTickets() as $singleTicket){
            if ($singleTicket->getId() === $data->id) {
                $ticket = $singleTicket;
            }
        }

        if ($ticket === null)
            return $errorControl->error(500, 'Ticket introuvable.');

        $order->removeTicket($ticket);

        $remainingTickets = $entityManager->getRepository(Date::class)->findOneBy(
            ['date' => $order->getDate()]
        );

        if($remainingTickets->getNbOfTickets() > 0) {
            $remainingTickets->setNbOfTickets($remainingTickets->getNbOfTickets() - 1);
        }

        $entityManager->persist($order);
        $entityManager->persist($remainingTickets);
        
        $entityManager->flush();

        $session->set('tickets', $order->getTickets());

        return $this->json(array(
            'success' => true,
            'message' => 'Billet supprimé',
        ));
    }

    /**
     * @Route("/api/get_tickets", name="api_get_tickets", methods={"GET"})
     */
    public function getTickets(SessionInterface $session, PriceControl $price) {
        $entityManager = $this->getDoctrine()->getManager();
        $order = $entityManager
            ->getRepository(Order::class)
            ->find($session->get('order')->getId());

        $tickets = [];

        foreach ($order->getTickets() as $singleTicket){
            $dateOfBirth = $singleTicket->getDateOfBirth();
            $object = (object) [
                'id' => $singleTicket->getId(),
                'firstName' => $singleTicket->getFirstName(),
                'lastName' => $singleTicket->getLastName(),
                'dateOfBirth' => $dateOfBirth,
                'price' => $price->getPriceHT($dateOfBirth, $singleTicket->getDiscount(), $singleTicket->getIsFullDay())->price,
                'priceName' => $price->getPriceHT($dateOfBirth, $singleTicket->getDiscount(), $singleTicket->getIsFullDay())->name,
                'isFullDay' => $singleTicket->getIsFullDay(),
            ];
            $tickets[] = $object;
        }
        return $this->json(array(
            'success' => true,
            'tickets' => $tickets,
            'totalHT' => $price->getTotalHT($order->getTickets()),
            'totalTTC' => $price->getTotalTTC($order->getTickets()),
            'TVA' => PriceControl::TVA,
        ));
    }

    /**
     * @Route("/api/get_remaining_tickets", name="api_get_remaining_tickets", methods={"GET"})
     */
    public function remainingTickets(Request $request, ErrorControl $errorControl) {

        $date = $request->query->get('date');

        if (!$date) {
            return $errorControl->error(500, 'Date attendue.');
        }

        $dateObject = new \DateTime($date);

        $normalizedDate = $dateObject->format('d/m/Y');

        $entityManager = $this->getDoctrine()->getManager();

        $entity = $entityManager->getRepository(Date::class)->findOneBy(
            ['date' => $dateObject]
        );

        $remaining = Date::MAX_TICKETS_PER_DAY;
        if(!empty($entity)) {
            $remaining = Date::MAX_TICKETS_PER_DAY - $entity->getNbOfTickets();
        }

        return $this->json(array(
            'success' => true,
            'message' => 'X Billets restants le ' . $normalizedDate,
            'remaining' => $remaining,
        ));
    }

}