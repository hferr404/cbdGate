<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Contact;
use App\Entity\Produit;
use App\Entity\Boutiques;
use App\Form\AddCommType;
use App\Entity\Commentaires;
use App\Form\CommentFormType;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\ProduitRepository;
use App\Repository\BoutiquesRepository;
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
     * @Route("/main/shop", name="shop")
     */

  
    public function show(Boutiques $boutique, Request $request, EntityManagerInterface $manager): Response
    {
       // $repoArticle = $this->getDoctrine()->getRepository(Article::class);
       // dump($repoArticle);
       // dump($id);

// On transmet à la méthode find() de la classe ArticleRepository l'id recupéré dans l'URL et transmit en argument de la fonction show($id) | $id = 3
// La méthode find() permet de selectionner en BDD un article par son ID
// on envoi sur le template les données selectionnées en BDD, c'est à dire les informations d'1 article en fonction l'id transmit dans l'URL

       $comment = new Commentaires;
       $formComment = $this->createForm(CommentFormType::class, $comment);

       $formComment->handleRequest($request);

       if ($formComment->isSubmitted() && $formComment->isValid()) 
       {
               $comment->setDateCreation(new \DateTime)
                       ->setBoutiques($boutique);

               $manager->persist($comment);
               $manager->flush(); 

               # app : varaible Twig qui contient toute les informations stockées en session #}
               # flashes() : méthode permettant d'accéder aux message utilisateur stockés en session #}
               # il peut y avoir plusieurs messages stockés donc nous sommes obligé de boucler #}
               # message est une variable de reception qui contient 1 message utilisateur par tour de boucle #}


               $this->addFlash('success', "Le commentaire a bien été posté");
               
               return $this->redirectToRoute('blog_show', [
                   "titre" => $boutique->getTitre()
               ]);
                                         
       }   



      //  $article = $repoArticle->find($id);
        //dump($articleCr);
        return $this->render('cbdGate/main/shop.html.twig', [
            "boutique" => $boutique,
            'formComment' => $formComment->createView()
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
     * @Route("/shop", name="main_shop")
     */


    public function shop(): Response
    {
       

       return $this->render('main/shop.html.twig');
       
    }

    /**
     * @Route("/contact", name="main_contact")
     */


    public function contact(Request $request, EntityManagerInterface $manager, ContactNotification $notification): Response
    {
       $contact=new Contact();
       $form=$this->createForm(ContactType::class,$contact);
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid())
       {
           $notification->notify($contact);
           $this->addFlash('success', 'Votre Email a bien été envoyé');
           $manager->persist($contact);
           $manager->flush();
       }

       return $this->render('main/contact.html.twig',[
           'formContact'=>$form->createView()
       ]);
    }
    
}
