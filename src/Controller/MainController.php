<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route ("/", name="main_home")
     */
    public function home(){
        return $this->render("main/home.html.twig");
    }

    /**
     * @Route ("/AboutUS", name="main_aboutUs")
     */
    public function aboutUs(): Response
    {
        return $this->render("main/aboutUs.html.twig");
    }
}