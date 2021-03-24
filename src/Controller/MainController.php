<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Produit;
use App\Form\AddCommType;
use App\Entity\Commentaires;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{

    /**

     * @Route("/main", name="home")

     */

    public function home(): Response
    {
        return $this->render('main/home.html.twig',[
        'produit' => 'CBDGATE LA MEILLEURE KUSH'
        ]);
    }



    /**
     * @Route("/main/index", name="main")
     */
    public function index(ProduitRepository $produitRepo): Response
    { // $repo = $this->getDoctrine()->getRepository(Article::class)

        $produitBdd = $produitRepo->findAll();

  
        return $this->render('main/index.html.twig', [
            'titre' => 'Listes des articles',
            'produitBdd' => $produitBdd
        ]);
    }


    /**
     * @Route("/main/show", name="main_show")
     */

    public function show(Produit $produit, Request $request, EntityManagerInterface $manager): Response
    {
     
        

        $comment = new Commentaires;
        $formComment = $this->createForm(AddCommType::class, $comment);

        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setDateCreation(new \DateTime)
                    ->setProduits($produit);

            $manager->persist($comment);
            $manager->flush();

         


            $this->addFlash('success', "Le commentaire a bien été posté");
               
            return $this->redirectToRoute('main_show', [
                //    "id" => $produit->getId()
               ]);
        }



       
        return $this->render('main/show.html.twig', [
            "produit" => $produit,
            'formComment' => $formComment->createView()
        ]);
    }


    
}
