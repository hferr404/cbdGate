<?php

namespace App\Controller;





use App\Entity\Membre;
use App\Entity\Produit;
use App\Entity\Boutiques;
use App\Entity\Categorie;
use App\Entity\Commentaires;
use App\Form\CommentFormType;
use App\Form\FormProduitType;
use App\Form\BoutiqueFormType;
use App\Form\CategorieFormType;
use App\Form\InscriptMembreType;
use App\Repository\MembreRepository;
use App\Repository\ProduitRepository;
use App\Repository\BoutiquesRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }



      /**
     * @Route("/admin/produit", name="admin_produit")
     * @Route("/admin/{id}/remove", name="admin_remove_produit")
     */

    public function adminProduit(EntityManagerInterface $manager, ProduitRepository $repoProduit, Produit $produit = null): Response
    {

        $colonnes = $manager->getClassMetadata(Produit::class)->getFieldNames();

  

       $produitBdd = $repoProduit->findAll();

     

       if($produit)
       {
           $id = $produit->getId();

           $manager->remove($produit);
           $manager->flush();

           $this->addFlash('success', "Le produit n°: $id " ."(". $produit->getTitre() . ")" . " a bien été supprimé");

           return $this->redirectToRoute('admin_produit');
       }

        return $this->render('admin/admin_produit.html.twig', [
            'colonnes' => $colonnes,
            'produit' => $produitBdd
        ]);
    }


  



    
       /**
        * 
        *@Route("/admin/produit/new", name="admin_add_produit")
        */
            public function adminAddProduit(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
            {
                
                
                $produit = new Produit;
                

                $produit->setTitre('CBD AUDI KUSH')
                        ->setContenu('SE FUME');



                $form = $this->createForm(FormProduitType::class, $produit);

                $form->handleRequest($request);

                dump($form['image']);


                if ($form->isSubmitted() && $form->isValid())
                {
                    /** @var UploadedFile $imageFile */    
                    $imageFile = $form->get('image')->getData();

                    dump($imageFile);

                    if($imageFile)
                    {
                    
                        $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                        dump($originalFileName);

                        $safeFileName = $slugger->slug($originalFileName);
                        dump($originalFileName);

                        $newFileName = $safeFileName . '-' . uniqid() . '.' . $imageFile->guessExtension();

                        try
                        {
                            $imageFile->move(
                                $this->getParameter('image_directory'),
                                $newFileName
                            );
                        }
                        catch(FileException $e)
                        {

                        }

                        $produit->setImage($newFileName);
                    }

                    if(!$produit->getId())
                    {
                        $produit->setDateCreation(new \DateTime);
                    }                                
                
        
                        $manager->persist($produit);
                        $manager->flush();


                        return $this->redirectToRoute('admin_produit', [
                            "id" => $produit->getId()
                        ]);
                    }

            

                    return $this->render("admin/admin_add_produit.html.twig", [
                    'formProduit' => $form->createView(),
                    
                ]);
                }



        

    /**
     * @Route("/admin/produit/edit", name="admin_edit_produit")
     * @Route("/admin/produit/{id}/edit", name="admin_edit_produit")
     */
    public function adminEditProduit(Request $request, EntityManagerInterface $manager, Produit $produit = null, SluggerInterface $slugger): Response
    {

        if(!$produit)
        {
            $produit = new Produit;
        }
      
        $formProduit = $this->createForm(FormProduitType::class, $produit);

        $formProduit->handleRequest($request); 
        


        if ($formProduit) {
            if ($formProduit->isSubmitted() && $formProduit->isValid())
             {
                
                 /** @var UploadedFile $imageFile */    
                 $imageFile = $formProduit->get('image')->getData();

                 dump($imageFile);

                 if($imageFile)
                 {
                 
                     $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                     dump($originalFileName);

                     $safeFileName = $slugger->slug($originalFileName);
                     dump($originalFileName);

                     $newFileName = $safeFileName . '-' . uniqid() . '.' . $imageFile->guessExtension();

                     try
                     {
                         $imageFile->move(
                             $this->getParameter('image_directory'),
                             $newFileName
                         );
                     }
                     catch(FileException $e)
                     {

                     }

                     $produit->setImage($newFileName);
                 }

                $manager->persist($produit);
                $manager->flush();

                $this->addFlash('success', "Le produit " . $produit->getId() . " a bien été modifié");
           
                return $this->redirectToRoute('admin_produit');
            }
        } 

        return $this->render('admin/admin_edit_produit.html.twig', [
            'formProduit' => $formProduit->createView()
        ]);  
    }
  

    


   


    
    /**
     * 
     * @Route("/admin/categorie", name="admin_categorie")
     * @Route("/admin/categorie/{id}/remove", name="admin_remove_categorie")
     */
    public function adminCategory(EntityManagerInterface $manager, CategorieRepository $repoCategorie, Categorie $categorie = null): Response
    {
        $colonnes = $manager->getClassMetadata(Categorie::class)->getFieldNames();

        dump($colonnes);
        dump($categorie);

        
        if($categorie)
        {

            if($categorie->getProduits()->isEmpty())
            {
                $manager->remove($categorie);
                $manager->flush();

                $this->addFlash('success', "La catégorie a été supprimée avec succès !");
            }

            else 
            {
                $this->addFlash('danger', "Il n'est pas possible de supprimer la catégorie car des articles y sont toujours associés !");
            }

            return $this->redirectToRoute('admin_categorie');
        }

        $categorieBdd = $repoCategorie->findAll(); 

        dump($categorieBdd);

        return $this->render('admin/admin_categorie.html.twig', [
            'colonnes' => $colonnes,
            'categorie' => $categorieBdd
        ]);
    }
     

    /**
     * @Route("/admin/categorie/edit", name="admin_form_categorie")
     * @Route("/admin/categorie/{id}/edit", name="admin_form_categorie")
     */
    public function adminEditCategorie(Request $request, EntityManagerInterface $manager, Categorie $categorie = null): Response
    {

        if(!$categorie)
        {
            $categorie = new Categorie;
        }

        $formCatego = $this->createForm(CategorieFormType::class, $categorie, [
            'validation_groups' => ['categorie']

        ]);

        $formCatego->handleRequest($request); 

        if($formCatego->isSubmitted() && $formCatego->isValid())
        {

                $manager->persist($categorie); 
                $manager->flush();

                $this->addFlash('success', "La catégorie " . $categorie->getId() . " a bien été modifiée");
           
            return $this->redirectToRoute('admin_categorie');
        }

        return $this->render('admin/admin_form_categorie.html.twig', [
            'formCatego' => $formCatego->createView()
        ]);  
    }
  
       /**
        * 
        *@Route("/admin/category/new", name="admin_new_categorie")
        *@Route("/admin/category/new", name="admin_add_categorie")
        */
        public function adminFormCategorie2(Request $request, EntityManagerInterface $manager): Response
        {
            $categorie = new Categorie;
            $formCatego = $this->createForm(CategorieFormType::class, $categorie,[
                'validation_groups' => ['categorie'] ]);

            $formCatego->handleRequest($request);

            if($formCatego->isSubmitted() && $formCatego->isValid())
            {
                $manager->persist($categorie);
                $manager->flush();

                $this->addFlash('success', "La catégorie  " . $categorie->getTitre() . " a bien été ajoutée");

                return $this->redirectToRoute("admin_categorie");
            } 

            return $this->render('admin/admin_add_categorie.html.twig', [
                'nameCatego' => $categorie->getTiTre(),
                'formCatego' => $formCatego->createView()
                ]);
        }



    /**
     * @Route("/admin/boutique", name="admin_boutique")
     * @Route("/admin/boutique/{id}/remove", name="admin_remove_boutique")
     */
    public function adminBoutique(EntityManagerInterface $manager, Boutiques $boutiques = null, BoutiquesRepository $repoBoutique): Response

    {
        $colonnes=$manager->getClassMetadata(Boutiques::class)->getFieldNames();

        $boutiquebdd=$repoBoutique->findAll();

        if($boutiques)
        {
            $manager->remove($boutiques);
            $manager->flush();
            return $this->redirectToRoute('admin_boutique');
        }
        return $this->render('admin/admin_boutique.html.twig',[
            'colonnes'=>$colonnes,
            'boutique'=>$boutiquebdd
        ]);
    }
  
  
    /**
     * @Route("/admin/boutique/ajout", name="admin_boutique_ajout")
     */
     public function boutiqueAjout(EntityManagerInterface $manager,Boutiques $boutique=null, Request $request): Response
     {
        if(!$boutique)
        {
            $boutique= new Boutiques;
        }
        $form=$this->createForm(BoutiqueFormType::class, $boutique);
        $form->handleRequest($request);
        dump($form);

        if($form->isSubmitted() && $form->isValid())
        {
            
            // $message='La boutique'. $boutiques->getTitre() . 'a bien été inserer';
            $manager->persist($boutique);
            $manager->flush();
            return $this->redirectToRoute('admin_boutique');

        }
    
        return $this->render('admin/admin_ajout_boutique.html.twig',[
            'form'=>$form->createView()
        ]);
     }
  

     /**
     * @Route("/admin/boutique/edit", name="admin_form_boutique")
     * @Route("/admin/boutique/{id}/edit", name="admin_edit_boutique")
     */
    public function boutiqueEdit(Boutiques $boutique=null,Request $request,EntityManagerInterface $manager): Response
    {
        $formBoutique=$this->createForm(BoutiqueFormType::class, $boutique);
        $formBoutique->handleRequest($request);

        if($formBoutique->isSubmitted() && $formBoutique ->isValid())
        {
            $manager->persist($boutique); // on prépare le requete de modification 
            $manager->flush();
            return $this->redirectToRoute('admin_boutique');
        }
        return $this->render('admin/admin_edit_boutique.html.twig', [
            'formBoutique' => $formBoutique->createView()
        ]);
    }

    
       

        
        /**
         * @Route("/admin/commentaires", name="admin_commentaires")
         * @Route("/admin/comment/{id}/remove", name="admin_remove_comment")
         */
        public function adminComment(Commentaires $comment = null, EntityManagerInterface $manager, CommentairesRepository $commentRepo): Response
        {
            $colonnes = $manager->getClassMetadata(Commentaires::class)->getFieldNames();
            $commentBdd = $commentRepo->findAll();


            dump($commentBdd);

            if($comment)
            {

                $id = $comment->getId();

                $auteur = $comment->getAuteur();

                $date = $comment->getDateCreation();

                $dateFormat = $date->format('Y-m-d à H:i:s');
                   
                   
                    $manager->remove($comment);
                    $manager->flush();

                    $this->addFlash('success', "Le commentaire n°$id, posté par $auteur, le $dateFormat a bien été supprimé");

                    return $this->redirectToRoute("admin_commentaires");
                 
                
                
            }


            return $this->render('admin/admin_commentaires.html.twig', [
                'commentBdd' => $commentBdd,
                'colonnes' => $colonnes
            ]);
        }

       
       
       
        /**
         * @Route("/admin/comment/{id}/edit", name="admin_edit_commentaires")
         */
        public function editComment(Commentaires $comment, Request $request, EntityManagerInterface $manager): Response
        {
            $commentForm = $this->createForm(CommentFormType::class, $comment);

            $commentForm->handleRequest($request);


             if ($commentForm != "produit" && $commentForm != "dateCreation") {
                 if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                     $manager->persist($comment);
                     $manager->flush();

                     $this->addFlash('success', "Le commentaire " . $comment->getId() . ", posté par " . $comment->getAuteur() .  " a bien été modifiée");

                     return $this->redirectToRoute("admin_comments");
                 }
             }

            return $this->render('admin/admin_edit_commentaires.html.twig', [
                'commentForm' => $commentForm->createView(),
                'commentId' => $comment->getId()
            ]);
        }


        
        


         /**
         *@Route("/admin/membre", name="admin_membre")
         *@Route("/admin/membre/{id}/remove", name="admin_remove_membre")
         *
         * 
         */
        public function adminUsers(EntityManagerInterface $manager, MembreRepository $membreRepo, Membre $membre = null): Response
        {
            $colonnes = $manager->getClassMetadata(Membre::class)->getFieldNames();
            $membreBdd = $membreRepo->findAll();


            dump($membreBdd);

          

            if ($membre)
             {
                $id = $membre->getId();

                $auteur = $membre->getUsername();


                $manager->remove($membre);
                $manager->flush();

                $this->addFlash('success', "L'utilisateur' n°$id =>  $auteur a bien été supprimé");

                return $this->redirectToRoute("admin_membre");
            }    
            

            return $this->render('admin/admin_membre.html.twig', [
                    'membreBdd' => $membreBdd,
                    'colonnes' => $colonnes
                ]);
        }


        
        /**
         * @Route("/admin/membre/{id}/edit", name="admin_edit_membre")
         */
        public function editMembre(Membre $membre, Request $request, EntityManagerInterface $manager): Response
        {
            $membreForm = $this->createForm(InscriptMembreType::class, $membre);

            $membreForm->handleRequest($request);


              
               
                  if ($membreForm->isSubmitted() && $membreForm->isValid())
                  {
                      $manager->persist($membre);
                      $manager->flush();

                       $this->addFlash('success', "L'utlisateur' " . $membre->getId() . " => " . $membre->getUsername() .  " a bien été modifiée");

                      return $this->redirectToRoute("admin_membre");
                  }
                  
             
            return $this->render('admin/admin_edit_membre.html.twig', [
                'membreForm' => $membreForm->createView(),
                'membreId' => $membre->getId(),
                'membreName' => $membre->getUsername()
            ]);
        }
    


    
}
