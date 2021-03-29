<?php

namespace App\Controller;

use App\Entity\Reaction;
use App\Entity\Wish;
use App\Form\ReactionType;
use App\Form\WishType;
use App\Repository\ReactionRepository;
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
    public function detail($id,Request $request,WishRepository $wishRepository,ReactionRepository $reactionRepository,EntityManagerInterface $entityManager): Response
    {
        $wish = $wishRepository->find($id);
        if(!$wish){
            throw $this->createNotFoundException('This wish don\'t exist!');
        }
        //Afficher les réactions d'un wish
        $reactions = $reactionRepository->findBy(["wish"=>$wish], ["dateCreated"=>"DESC"], 10);

        //Créer une réaction vide
        $reaction = new Reaction();
        //Créer le formulaire
        $reactionForm = $this->createForm(ReactionType::class,$reaction);
        //Récupérer les données soumises
        $reactionForm->handleRequest($request);
        //Vérifier si le formulaire a été bien soumis et valide
        if($reactionForm->isSubmitted()&&$reactionForm->isValid()){
            //Hydrate les données manquantes
            $reaction->setDateCreated(new \DateTime());
            $reaction->setWish($wish);//créer la relation entre reaction et wish
            //Sauvegarder dans le BDD
            $entityManager->persist($reaction);
            $entityManager->flush();
            //Gérer les falsh messages
            $this->addFlash('success', "Thank you for your reaction !");
            //Redirection
            return $this->redirectToRoute("wish_detail", ['id'=>$wish->getId()]);
        }
        return $this->render('wish/detail.html.twig', ["wish" =>$wish, "reactions"=>$reactions,"reactionForm"=>$reactionForm->createView()]);
    }

    /**
     * @Route("/wish/new", name="wish_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager):Response
    {
        //Crée un wish vide pour que Symfony puisse y injecter les données, pouvoir récupérer un wish de BDD et le modifier dans le formulaire
        $wish = new Wish();
        if($this->getUser()){
            $wish->setAuthor($this->getUser()->getUsername());
        }
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
