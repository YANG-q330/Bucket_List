<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/wish/list", name="wish_list")
     */
    public function list(): Response
    {
        //todo requête à la BDD pour aller chercher la liste
        return $this->render('wish/list.html.twig', [
        ]);
    }

    /**
     * @Route("/wish/{id}", name="wish_detail")
     */
    public function detail($id): Response
    {
        //todo requête à la BDD pour aller chercher les infos de ce wish dont id est dans l'URL
        return $this->render('wish/detail.html.twig');
    }

}
