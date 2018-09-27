<?php

namespace App\EventSubscriber;

use App\Events;
use App\Entity\Order;
use App\Utils\PriceControl;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class OrderPlacedSubscriber
 * @package App\EventSubscriber
 */
class OrderPlacedSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $session;
    private $price;
    private $templating;

    /**
     * OrderPlacedSubscriber constructor.
     * @param \Swift_Mailer $mailer
     * @param SessionInterface $session
     * @param PriceControl $price
     * @param \Twig_Environment $templating
     */
    public function __construct(\Swift_Mailer $mailer, SessionInterface $session, PriceControl $price, \Twig_Environment $templating)
    {
        // On injecte notre expediteur et la classe pour envoyer des mails
        $this->mailer = $mailer;
        $this->session = $session;
        $this->price = $price;
        $this->templating = $templating;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            // le nom de l'event et le nom de la fonction qui sera déclenché
            Events::ORDER_PLACED => 'onOrderPlaced',
        ];
    }

    /**
     * @param GenericEvent $event
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onOrderPlaced(GenericEvent $event): void
    {
        $session =  $this->session;
        $order = $session->get('order');
        $tickets = $session->get('tickets');

        $subject = "Confirmation";

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom('musee@louvre.com')
            ->setTo($order->getEmail())
            ->setBody(
                $this->templating->render(
                // templates/emails/registration.html.twig
                    'emails/confirmation.html.twig', array(
                        'order' => $order,
                        'tickets' => $tickets,
                        'totalHT' => $this->price->getTotalHT($tickets),
                        'totalTTC' => $this->price->getTotalTTC($tickets),
                        'TVA' => PriceControl::TVA,
                    )
                ),
                'text/html'
            );
        ;

        $this->mailer->send($message);
        $session->invalidate();
    }
}


