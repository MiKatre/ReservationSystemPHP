<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Ticket;
use App\Entity\Date;
use App\Utils\ErrorControl;
use App\Utils\PriceControl;
use App\Utils\TicketService;
use App\Utils\DateControl;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Responders\TicketResponder;

/**
 * Class TicketController
 * @package App\Controller
 */
class TicketController extends AbstractController
{
    /**
     * @param Request $request
     * @param ErrorControl $errorControl
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/api/add_ticket", name="api_add_ticket", methods={"POST"})
     */
    public function addTicket(Request $request, ErrorControl $errorControl, TicketService $ticketService, TicketResponder $ticketResponder){
        // Return errorMessage or success message with the id

        if ($request->getContentType() != 'json' || !$request->getContent())
            return $errorControl->error(500, 'Mauvais format de données. JSON attendu.');

        $data = json_decode($request->getContent());
        $order = $ticketService->addTicketToOrder($data);

        return $ticketResponder->addTicketRes($order);
    }


    /**
     * @param Request $request
     * @param SessionInterface $session
     * @param ErrorControl $errorControl
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/api/remove_ticket", name="api_remove_ticket", methods={"DELETE"})
     */
    public function removeTicket(Request $request, ErrorControl $errorControl, TicketService $ticketService, TicketResponder $ticketResponder) {

        if ($request->getContentType() != 'json' || !$request->getContent()) {
            return $errorControl->error(500, 'Mauvais format de données. JSON attendu.');
        }

        $data = json_decode($request->getContent());

        // Return true or HTTP Response object
        $isTicketRemoved = $ticketService->remove($data);

        //
        if (!$isTicketRemoved)
            return $isTicketRemoved;

        return $ticketResponder->removeTicketRes();
    }

    /**
     * @param SessionInterface $session
     * @param PriceControl $price
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/api/get_tickets", name="api_get_tickets", methods={"GET"})
     */
    public function getTickets(SessionInterface $session, TicketResponder $ticketResponder) {
        $entityManager = $this->getDoctrine()->getManager();
        $order = $entityManager
            ->getRepository(Order::class)
            ->find($session->get('order')->getId());

        return $ticketResponder->getTicketsRes($order);
    }

    /**
     * @param Request $request
     * @param ErrorControl $errorControl
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/api/get_remaining_tickets", name="api_get_remaining_tickets", methods={"GET"})
     */
    public function remainingTickets(Request $request, ErrorControl $errorControl, TicketResponder $ticketResponder, TicketService $ticketService) {

        $date = $request->query->get('date');
        if (!$date)
            return $errorControl->error(500, 'Date attendue.');

        $remaining = $ticketService->remaining($date);

        return $ticketResponder->remainingTicketsRes($remaining);
    }

    /**
     * @param SessionInterface $session
     * @param DateControl $dateControl
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/api/allow_full_day", name="api_allow_full_day", methods={"GET"})
     */
    public function allowFullDay(SessionInterface $session, DateControl $dateControl){
        return $this->json(array(
            'allowFullDay' => $dateControl->allowFullDay($session->get('order')->getDate()),
        ));
    }

}