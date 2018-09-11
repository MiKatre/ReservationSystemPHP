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
use Symfony\Component\HttpFoundation\Session\Session;
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
    public function createOrder(Request $request, ValidatorInterface $validator, SessionInterface $session) {

       if ($request->getContentType() != 'json' || !$request->getContent()) {
           $error = new Response();
           $error->setContent('Mauvais format de données. JSON attendu.');
           $error->setStatusCode(500);
           return $error;
       }

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
        ));
    }

    /**
     * @Route("/api/get_tickets", name="api_get_tickets", methods={"POST"})
     */
    public function getTickets(SessionInterface $session) {
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
                'price' => $this->getPrice($dateOfBirth, $singleTicket->getDiscount())->price,
                'priceName' => $this->getPrice($dateOfBirth, $singleTicket->getDiscount())->name,
                'isFullDay' => $singleTicket->getIsFullDay(),
            ];
            $tickets[] = $object;
        }
        return $this->json(array(
            'success' => true,
            'tickets' => $tickets,
        ));
    }

    /**
     * @Route("/api/add_ticket", name="api_add_ticket", methods={"POST"})
     */
    public function addTicket(Request $request, ValidatorInterface $validator, SessionInterface $session){
        // Return errorMessage or success message with the id

        if ($request->getContentType() != 'json' || !$request->getContent()) {
            $error = new Response();
            $error->setContent('Mauvais format de données. JSON attendu.');
            $error->setStatusCode(500);
            return $error;
        }

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
        $ticket->setPrice($this->getPrice($dateOfBirth, $data->discount)->price);
        $ticket->setIsFullDay($data->isFullDay);

        $order->addTicket($ticket);

        $entityManager->persist($order);

        $entityManager->flush();

        $tickets = [];

        foreach ($order->getTickets() as $singleTicket){
            $dateOfBirth = $singleTicket->getDateOfBirth();
            $object = (object) [
                'id' => $singleTicket->getId(),
                'firstName' => $singleTicket->getFirstName(),
                'lastName' => $singleTicket->getLastName(),
                'dateOfBirth' => $dateOfBirth,
                'price' => $this->getPrice($dateOfBirth, $singleTicket->getDiscount())->price,
                'priceName' => $this->getPrice($dateOfBirth, $singleTicket->getDiscount())->name,
                'isFullDay' => $singleTicket->getIsFullDay(),
            ];
            $tickets[] = $object;
        }

        return $this->json(array(
            'success' => true,
            'tickets' => $tickets,
            'message' => 'Billet ajouté',
        ));
    }

    private function getAge($dateOfBirth) {
        $diff = $dateOfBirth->diff(new \DateTime());
        return (int) ($diff->format('%y'));
    }

    private function getPrice($dateOfBirth, $discount) {
        $age = (int) $this->getAge($dateOfBirth);
        // à partir de 12 ans
        $normal = (object) ['price' => 1600, 'name' => 'Normal'];
        // de 4 à 12 ans
        $children = (object) ['price' => 800, 'name' => 'Enfant'];
        // à partir de 60 ans
        $senior = (object) ['price' => 1200, 'name' => 'Senior'];
        // Moins de 4 ans
        $free = (object) ['price' => 0, 'name' => 'Enfant'];


        return ($age >= 4 && $age <= 12 ? ($children) : ($age >= 60 ? ($senior) : ($age < 4 ? ($free) : ($normal))));
    }

    /**
     * @Route("/api/remove_ticket", name="api_remove_ticket", methods={"POST"})
     */
    public function removeTicket(Request $request, SessionInterface $session) {

        if ($request->getContentType() != 'json' || !$request->getContent()) {
            $error = new Response();
            $error->setContent('Mauvais format de données. JSON attendu.');
            $error->setStatusCode(500);
            return $error;
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

        if ($ticket === null) {
            $error = new Response();
            $error->setContent('Ticket introuvable.');
            $error->setStatusCode(500);
            return $error;
        }

        $order->removeTicket($ticket);

        $entityManager->persist($order);

        $entityManager->flush();

        return $this->json(array(
            'success' => true,
            'message' => 'Billet supprimé',
        ));

        // Check the request
        // Extract the id from the request
        // Remove ticket from db
        // successMessage or errorMessage
    }

    public function remainingTickets(Request $request) {
        // Make sure the request is valid and contains a valid date
        // Else return an errorMessage
        // Extract the date from the request
        // Ask the db for the remaining tickets for that date
        // Return remaining tickets
    }
}