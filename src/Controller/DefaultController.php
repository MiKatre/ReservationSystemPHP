<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\PriceControl;

class DefaultController extends AbstractController
{
    /**
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="site_homepage")
     */
    public function homepage(SessionInterface $session) 
    {
        $session->start();
        
        return $this->render('commande/homepage.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/form", name="site_form")
     */
    public function showForm(){
        return $this->render('commande/form.html.twig');
    }
}