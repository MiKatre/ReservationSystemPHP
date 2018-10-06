<?php

namespace App\Controller;

use App\Responder\OrderResponder;
use App\Utils\DateControl;
use App\Utils\ErrorControl;
use App\Utils\OrderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{

    /**
     * @param Request $request
     * @param DateControl $dateControl
     * @param ErrorControl $errorControl
     * @param OrderService $orderService
     * @param OrderResponder $orderResponder
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     *
     *  @Route("/api/create_order", name="api_create_order", methods={"POST"})
     */
    public function createOrder(Request $request, DateControl $dateControl, ErrorControl $errorControl, OrderService $orderService, OrderResponder $orderResponder) {

       if ($request->getContentType() != 'json' || !$request->getContent())
           return $errorControl->error(400, 'Mauvais format de données. JSON attendu.');

        $data = json_decode($request->getContent());

        if (!$dateControl->isDateValid(new \DateTime($data->date)))
            return $errorControl->error(500, "Date invalide");

        $result = $orderService->create($data);

        return $orderResponder->createOrderRes($result);
    }
}