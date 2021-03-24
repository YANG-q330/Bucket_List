<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/wish/list", name="wish_list")
     */
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy(["isPublished"=>true],['dateCreated' => 'DESC'],10,0);
        return $this->render('wish/list.html.twig', [
            "wishes" =>$wishes
        ]);
    }

    /**
     * @Route("/wish/{id}", name="wish_detail")
     */
    public function detail($id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);
        if(!isset($wish)){
            throw $this->createNotFoundException('This wish don\'t exist');
        }else{
            return $this->render('wish/detail.html.twig', ["wish" =>$wish]);
        }
    }
}
