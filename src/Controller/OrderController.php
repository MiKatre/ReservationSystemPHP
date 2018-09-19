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
use App\Entity\Date;
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
            'reservationCode' => $order->getReservationCode(),
        ));
    }

    /**
     * @Route("/api/get_tickets", name="api_get_tickets", methods={"GET"})
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
                'price' => $this->getPrice($dateOfBirth, $singleTicket->getDiscount(), $singleTicket->getIsFullDay())->price,
                'priceName' => $this->getPrice($dateOfBirth, $singleTicket->getDiscount(), $singleTicket->getIsFullDay())->name,
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
        $ticket->setPrice($this->getPrice($dateOfBirth, $data->discount, $data->isFullDay )->price);
        $ticket->setIsFullDay($data->isFullDay);

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

        $tickets = [];

        foreach ($order->getTickets() as $singleTicket){
            $dateOfBirth = $singleTicket->getDateOfBirth();
            $object = (object) [
                'id' => $singleTicket->getId(),
                'firstName' => $singleTicket->getFirstName(),
                'lastName' => $singleTicket->getLastName(),
                'dateOfBirth' => $dateOfBirth,
                'price' => $this->getPrice($dateOfBirth, $singleTicket->getDiscount(), $singleTicket->getIsFullDay())->price,
                'priceName' => $this->getPrice($dateOfBirth, $singleTicket->getDiscount(), $singleTicket->getIsFullDay())->name,
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

    private function getPrice($dateOfBirth, $discount, $isFullDay) {
        $age = (int) $this->getAge($dateOfBirth);

        // à partir de 12 ans
        $normal = (object) ['price' => Ticket::NORMAL_PRICE, 'name' => 'Normal'];
        // de 4 à 12 ans
        $children = (object) ['price' => Ticket::CHILDREN_PRICE, 'name' => 'Enfant'];
        // à partir de 60 ans
        $senior = (object) ['price' => Ticket::SENIOR_PRICE, 'name' => 'Senior'];
        // Moins de 4 ans
        $baby = (object) ['price' => Ticket::BABY_PRICE, 'name' => 'Enfant'];

        if ($age >= 4 && $age <= 12)
            $formula = $children;
        else if($age >= 60)
            $formula = $senior;
        else if($age < 4 )
            $formula = $baby;
        else
            $formula = $normal;

        if ($formula->price > 0) {
            $formula->price = $isFullDay ? $formula->price : $formula->price * Ticket::HALF_DAY_DISCOUNT_PERCENT;
            $formula->price = $discount ? $formula->price - ($formula->price * Ticket::DISCOUNT_PERCENT) : $formula->price;
        }

        return $formula;
    }

    /**
     * @Route("/api/remove_ticket", name="api_remove_ticket", methods={"DELETE"})
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

        $remainingTickets = $entityManager->getRepository(Date::class)->findOneBy(
            ['date' => $order->getDate()]
        );

        if($remainingTickets->getNbOfTickets() > 0) {
            $remainingTickets->setNbOfTickets($remainingTickets->getNbOfTickets() - 1);
        }

        $entityManager->persist($order);
        $entityManager->persist($remainingTickets);

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

    /**
     * @Route("/api/get_remaining_tickets", name="api_get_remaining_tickets", methods={"GET"})
     */
    public function remainingTickets(Request $request) {

        $date = $request->query->get('date');

        if (!$date) {
            $error = new Response();
            $error->setContent('Date attendue.');
            $error->setStatusCode(500);
            return $error;
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

    /**
     * @Route("/api/pay", name="api_pay", methods={"POST"})
     */
    public function pay(Request $request, SessionInterface $session){

        if ($request->getContentType() != 'json' || !$request->getContent())
            return $this->customError(500, 'Mauvais format de données. JSON attendu.');

        $data = json_decode($request->getContent());

        if (!isset($data->token) || empty($data->token))
            return $this->customError(500, 'No token received');

        if (!isset($data->token->id) || empty($data->token->id))
            return $this->customError(500, 'Payload received without id');

        $token = $data->token->id;

        $entityManager = $this->getDoctrine()->getManager();
        $order = $entityManager
            ->getRepository(Order::class)
            ->find($session->get('order')->getId());

        $tickets = $order->getTickets();

        $amount = 0;

        foreach($tickets as $ticket) {
            $amount = $amount + $ticket->getPrice();
        }

        if ($amount <= 0){
            return $this->customError(500, 'Anormal amount');
        }

        \Stripe\Stripe::setApiKey("sk_test_3xhumvzEnBe3JnTJeFS7u67i");

        $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => 'eur',
            'source' => $token,
            'receipt_email' => $session->get('order')->getEmail(),
        ]);

        // $charge->paid === true
        // $charge->status === 'succeeded'
        // $charge->outcome->seller_message === 'Payment complete.'
        if($charge->paid === true) {
            $order->setIsPaid(true);
            $entityManager->persist($order);
            $entityManager->flush();
            $session->invalidate();
        }

        $this->sendEmail($order, $tickets, $mailer, $price, $session); //TicketBilled

        return $this->json(array(
            'paid' => $charge->paid,
            'email' => $charge->receipt_email,
            'message' => "Commande passée avec succès"
        ));
    }

    public function customError($statusCode, $message) {
        $error = new Response();
        $error->setContent($message);
        $error->setStatusCode( (int) $statusCode);
        return $error;
    }

    private function sendEmail(Order $order, $tickets, \Swift_Mailer $mailer, PriceControl $price, SessionInterface $session) {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo($order->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/confirmation.html.twig', array(
                        'order' => $order,
                        'tickets' => $tickets,
                        'totalHT' => $price->getTotalHT($order->getTickets()),
                        'totalTTC' => $price->getTotalTTC($order->getTickets()),
                        'TVA' => PriceControl::TVA,
                    )
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;

        $mailer->send($message);
        $session->invalidate();
        return 0;
    }
}


//return JsonResponse::fromJsonString($request->getContent());