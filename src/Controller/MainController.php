<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Produit;
use App\Entity\Comments;
use App\Entity\Boutiques;
use App\Form\AddCommType;
use App\Entity\Commentaires;
use App\Form\CommentFormType;
use App\Entity\CommentProduit;
use App\Form\CommentProdFormType;
use App\Repository\ProduitRepository;
use App\Repository\BoutiquesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\FormCommentType;

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
     * @Route("/", name="main")
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
     * @Route("/boutiques", name="main_boutiques")
     */
     public function boutiques(BoutiquesRepository $boutiquesrepo): Response
     {
        dump($boutiquesrepo);

        $boutiques = $boutiquesrepo->findAll();

        return $this->render('main/boutiques.html.twig', [

            'boutiques' => $boutiques
        ]);
        
     }

    /**
     * @Route("/shop/{id}", name="main_shop")
     * 
     */
    public function shop(EntityManagerInterface $manager, Request $request, Boutiques $boutique = null, ProduitRepository $produitRepo): Response
    {
        if (!$boutique) {
            $boutique = new Boutiques;
        }

        $produit = $produitRepo->findAll();
        
        $user = $this->getUser();

       
        $comment = new Comments;
        $formComment = $this->createForm(FormCommentType::class, $comment);
 
        $formComment->handleRequest($request);
 
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setDateCreation(new \DateTime)
                       
                        ->setBoutiques($boutique)
                        ->setAuteur($user->getUsername());
                
              
            $manager->persist($comment);
            $manager->flush();
               
            return $this->redirectToRoute('main_shop', [
                    "id" => $boutique->getId()
                ]);
        }

        return $this->render('main/shop.html.twig', [
            "boutique" => $boutique,
            "produit" => $produit,
            'formComment' => $formComment->createView()
        ]);
    }

        
    /**
     * @Route("/produit/{id}", name="main_produit")
     * 
     */
    public function produit(EntityManagerInterface $manager, Request $request, Produit $produit = null): Response
    {
        if (!$produit) 
        {
            $produit = new Produit;
        }
        
        $user = $this->getUser();

        $comment = new CommentProduit;
        $formComment = $this->createForm(CommentProdFormType::class, $comment);
 
        $formComment->handleRequest($request);
 
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setDateCreation(new \DateTime)
                       
                        ->setProduits($produit)
                        ->setAuteur($user->getUsername());
                
              
                
            $manager->persist($comment);
            $manager->flush();
 
               
            return $this->redirectToRoute('main_produit', [
                    "id" => $produit->getId()
                ]);
        }
    
    
       return $this->render('main/produit.html.twig', [
        "produit" => $produit,
        'formComment' => $formComment->createView()
    ]);
       
    }



    /**
     * @Route("/contact", name="main_contact")
     */


    public function contact(): Response
    {
       

       return $this->render('main/contact.html.twig');
    }
    
}
