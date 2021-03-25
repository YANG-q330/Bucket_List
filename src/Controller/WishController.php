<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/wish/list/{page}", name="wish_list", requirements={"page": "\d+"})
     */
    public function list(WishRepository $wishRepository, int $page=1): Response
    {
        $result = $wishRepository->findWishList($page);
        $wishes = $result['result'];

        return $this->render('wish/list.html.twig', [
            "wishes" =>$wishes,
            "totalResultCount"=>$result['totalResultCount'],
            "currentPage"=>$page
        ]);
    }

    /**
     * @Route("/wish/{id}", name="wish_detail", requirements={"id": "\d+"})
     */
    public function detail($id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);
        if(!$wish){
            throw $this->createNotFoundException('This wish don\'t exist!');
        }
        return $this->render('wish/detail.html.twig', ["wish" =>$wish]);
    }

    /**
     * @Route("/wish/new", name="wish_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager):Response
    {
        //Crée un wish vide pour que Symfony puisse y injecter les données, pouvoir récupérer un wish de BDD et le modifier dans le formulaire
        $wish = new Wish();
        //Crée le formulaire
        $wishForm = $this->createForm(WishType::class, $wish);
        //Récupère les données soumises
        $wishForm->handleRequest($request);
        //Vérifier si le formulaire a été bien soumis et valide
        if($wishForm->isSubmitted() && $wishForm->isValid()){
            //Hydrate les propriétés manquantes
            $wish->setLikes(0);
            $wish->setDateCreated(new \DateTime());
            $wish->setIsPublished(true);
        //Sauvegarder dans le BDD
        $entityManager->persist($wish);
        $entityManager->flush();
        //Gérer les flash messages sur la prochaine page
        $this->addFlash("success", "Idea successfully added!");
        //Redirection
        return $this->redirectToRoute("wish_detail",['id'=>$wish->getId()]);
        }
        return $this->render("wish/new.html.twig",[
            "wishForm" =>$wishForm->createView()
        ]);
    }
}
