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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/", name="site_homepage")
     */
    public function homepage() {

        $entityManager = $this->getDoctrine()->getManager();

        $price = $entityManager
            ->getRepository('App:Price')
            ->find(1);

//        $price = new Price();
//        $price->setName('kid');
//        $price->setPrice(300);
        $date = date('m/d/Y h:i:s a', time());

        $order = new Order();
        $order->setFirstName('Pauline');
        $order->setLastName('Alg');
        $order->setEmail('pauline.algebra@organisation.org');
        $order->setReservationCode('X06h71k');
        $order->setDate(new \DateTime("10-10-1990"));

        $ticket = new Ticket();
        $ticket->setFirstName('Pauline');
        $ticket->setLastName('Algebra');
        $ticket->setDateOfBirth(new \DateTime("10-10-1990"));
        $ticket->setDiscount(true);
        $ticket->setPrice($price->getPrice());
//        $ticket->setOrderRelation($order);

        $order->addTicket($ticket);

        //$entityManager->persist($order);



        //$entityManager->flush();

        return $this->render('commande/homepage.html.twig', array('order' => $order));
    }

    /**
     * @Route("/form", name="site_form")
     */
    public function showForm(){
        return $this->render('commande/form.html.twig');
    }

    /**
     * @Route("/commande/{id}")
     */
    public function showOrder($id){
        $repo = $this->getDoctrine()->getManager()->getRepository('App:Order');

        $order = $repo->find(1);

        return new Response("La commande " . $order->getReservationCode() . " a été passée au nom de " . $order->getFirstName() . " " .  $order->getLastName());
    }

    /**
     * @Route("/api/create_order", name="api_create_order", methods={"POST"})
     */
    public function createOrder(Request $request, ValidatorInterface $validator) {

        //return JsonResponse::fromJsonString($request->getContent());
       if ($request->getContentType() != 'json' || !$request->getContent()) {
           $error = new Response();
           $error->setContent('Mauvais format de données. JSON attendu.');
           $error->setStatusCode(500);
           return $error;
       }

        $data = json_decode($request->getContent());

        //return $this->json($data->firstName);

        $order = new Order();
        $order->setFirstName($data->firstName);
        $order->setLastName($data->lastName);
        $order->setEmail($data->email);
        $order->setDate(new \DateTime($data->date));
        $order->setReservationCode('X06h71k');                 // Generate one ? Redundant with id ?

        $errors = $validator->validate($order);

        if(count($errors) > 0) {
            // $errorString = (string) $errors;
            $errorsArray = [];

            foreach ($errors as $violation) {
                $errorsArray[] = $violation->getMessage();
            }
            return $this->json(array('errors' => $errorsArray));
        }

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($order);

        $entityManager->flush();

        return $this->json(array('username' => 'John Doe'));
    }
    
}