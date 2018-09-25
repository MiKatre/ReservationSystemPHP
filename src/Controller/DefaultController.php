<?php

namespace App\Controller;

use App\Utils\DateControl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
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
}