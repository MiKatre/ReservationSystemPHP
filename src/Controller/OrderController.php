<?php

namespace App\Controller;

use App\Entity\Order;
use App\Utils\DateControl;
use App\Utils\ErrorControl;
use App\Utils\PriceControl;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/", name="site_homepage")
     */
    public function homepage(SessionInterface $session) {
        $session->start();
        return $this->render('commande/homepage.html.twig');
    }

    /**
     * @Route("/form", name="site_form")
     */
    public function showForm(){
        return $this->render('commande/form.html.twig');
    }


    /**
     * @Route("/api/allow_full_day", name="api_allow_full_day", methods={"GET"})
     */
    public function allowFullDay(SessionInterface $session, DateControl $dateControl){
        return $this->json(array(
            'allowFullDay' => $dateControl->allowFullDay($session->get('order')->getDate()),
        )); 
    }

    /**
     * @Route("/api/create_order", name="api_create_order", methods={"POST"})
     */
    public function createOrder(Request $request, ValidatorInterface $validator, SessionInterface $session, DateControl $dateControl, ErrorControl $errorControl) {

       if ($request->getContentType() != 'json' || !$request->getContent())
           return $errorControl->error(500, 'Mauvais format de données. JSON attendu.');

        $data = json_decode($request->getContent());
        $entityManager = $this->getDoctrine()->getManager();

        $order = $session->has('order') ?
            $entityManager
                ->getRepository(Order::class)
                ->find($session->get('order')
                    ->getId() )
            : new Order();

        $order->setFirstName($data->firstName);
        $order->setLastName($data->lastName);
        $order->setEmail($data->email);

        if (!$dateControl->isDateValid(new \DateTime($data->date))){
            return $errorControl->error(500, "Date invalide");
        }

        $order->setDate(new \DateTime($data->date));

        // If we are creating a new order
        if (!$session->has('order')) {
            // Generate a random reservation code.
            $random = bin2hex(random_bytes(4));
            $order->setReservationCode($random);
        }

        $errors = $validator->validate($order);

        if(count($errors) > 0) {
            $errorsArray = [];

            foreach ($errors as $violation) {
                $errorsArray[] = $violation->getMessage();
            }
            return $this->json(array('errors' => $errorsArray));
        }

        $entityManager->persist($order);

        $entityManager->flush();

        $message = $session->has('order') ? 'Commande mise à jour' : 'Commande créée' ;

        $session->set('order', $order);

        return $this->json(array(
            'success' => true,
            'message' =>  $message,
            'reservationCode' => $order->getReservationCode(),
        ));
    }
}