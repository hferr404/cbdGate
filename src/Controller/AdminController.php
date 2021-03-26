<?php

namespace App\Controller;

use App\Entity\Boutiques;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\BoutiqueFormType;
use App\Form\FormProduitType;
use App\Form\CategorieFormType;
use App\Repository\BoutiquesRepository;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/admin/edit", name="admin_edit_produit")
     */
    public function create(Produit $produit = null, Request $request, EntityManagerInterface $manager): Response
    {
        if (!$produit)
         {
            $produit = new Produit;
        }

        $produit->setTitre('CBD AUDI KUSH')
                  ->setContenu('SE FUME');

                

        $form = $this->createForm(FormProduitType::class, $produit);

        $form->handleRequest($request);

     

        if ($form->isSubmitted() && $form->isValid())
         {

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
             
      

            return $this->render("admin/admin_edit_produit.html.twig", [
            'formProduit' => $form->createView(),
            'editMode' => $produit->getId()
        ]);
        }
    


   


    
    /**
     * Méthode permettant d'afficher sous forme de tableau HTML les catégories stockées en BDD
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

        $categorieBdd = $repoCategorie->findAll(); // SELECT * FROM category + FETCH_ALL

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
    public function adminFormCategorie(Request $request, EntityManagerInterface $manager, Categorie $categorie = null): Response
    {
        if(!$categorie)
        {
            $categorie = new Categorie;
        }

        $formCatego = $this->createForm(CategorieFormType::class, $categorie, [
            'validation_groups' => ['categorie']
        ]);

        dump($request);

        $formCatego->handleRequest($request);

        dump($categorie);

        if($formCatego->isSubmitted() && $formCatego->isValid())
        {
            if(!$categorie->getId())
                $message = "La catégorie " . $categorie->getTitre() . " a été enregistrée avec succès !";
            else 
                $message = "La catégorie " . $categorie->getTitre() . " a été modifiée avec succès !";

            $manager->persist($categorie);
            $manager->flush();

            return $this->redirectToRoute('admin_categorie');
        }

        return $this->render('admin/admin_form_categorie.html.twig', [
            'formCatego' => $formCatego->createView()
        ]);  
    }

    /**
     * @Route("/admin/boutique", name="admin_boutique")
     */
    public function adminBoutique(EntityManagerInterface $manager, Boutiques $boutiques = null, BoutiquesRepository $repoBoutique): Response

    {
        $colonnes=$manager->getClassMetadata(Boutiques::class)->getFieldNames();

        $boutique=$repoBoutique->findAll();
        return $this->render('admin/admin_boutique.html.twig',[
            'colonnes'=>$colonnes,
            'boutique'=>$boutique
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
    
}
