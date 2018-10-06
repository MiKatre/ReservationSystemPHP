<?php

namespace App\Controller;

use App\Responder\PaiementResponder;
use App\Utils\ErrorControl;
use App\Utils\PaiementService;
use App\Utils\PriceControl;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class PaiementController extends AbstractController
{
    /**
     * @param Request $request
     * @param ErrorControl $errorControl
     * @param PaiementService $paiementService
     * @param PaiementResponder $paiementResponder
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/api/pay", name="api_pay", methods={"POST"})
     */
    public function pay(Request $request, ErrorControl $errorControl, PaiementService $paiementService, PaiementResponder $paiementResponder){

        if ($request->getContentType() != 'json' || !$request->getContent())
            return $errorControl->error(500, 'Mauvais format de données. JSON attendu.');

        $data = json_decode($request->getContent());

        if (!isset($data->token) || empty($data->token))
            return $errorControl->error(500, 'No token received');

        if (!isset($data->token->id) || empty($data->token->id))
            return $errorControl->error(500, 'Payload received without id');

        $result = $paiementService->pay($data);
        return $paiementResponder->paidRes($result);

    }
}