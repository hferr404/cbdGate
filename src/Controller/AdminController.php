<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\FormProduitType;
use App\Form\CategoryFormType;
use App\Form\CategorieFormType;
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

           $this->addFlash('success', "L'article n°: $id a bien été supprimé");

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
            }
        //     // return $this->redirectToRoute('produit_show', [
        //     //   "id" => $produit->getId()
        //   ]);
      

            return $this->render("admin/admin_edit_produit.html.twig", [
            'formProduit' => $form->createView(),
            'editMode' => $produit->getId()
        ]);
        }
    


    // /**
    //  * @Route("/admin/edit", name="admin_edit_produit")
    //  */

    // public function adminEditProduit(Produit $produit, Request $request, EntityManagerInterface $manager)
    // {
    //     $formProduit = $this->createForm(FormProduitType::class, $produit);

    //     $formProduit->handleRequest($request);

    //     if ($formProduit->isSubmitted() && $formProduit->isValid()) {
    //         $manager->persist($produit);
    //         $manager->flush();

    //         // $this->addFlash('success', "Le produit " . $produit->getId() . " a bien été modifié");  

    //         return $this->redirectToRoute('admin_produit');
    //     }

    //     return $this->render('admin/admin_edit_produit.html.twig', [
    //         //   "idProduit" => $produit->getId(),
    //           "formProduit" => $formProduit->createView()
    //       ]);
    // }



    
    /**
     * Méthode permettant d'afficher sous forme de tableau HTML les catégories stockées en BDD
     * 
     * @Route("/admin/categorie", name="admin_categorie")
     * @Route("/admin/categorie/remove", name="admin_remove_category")
     */
    public function adminCategory(EntityManagerInterface $manager, CategorieRepository $repoCategorie, Categorie $categorie): Response
    {
        $colonnes = $manager->getClassMetadata(Categorie::class)->getFieldNames();

        dump($colonnes);
        dump($categorie);

        // Si la variable $category retourne TRUE,cela veut dire qu'elle contient une categorie de la BDD, alors on entre dans le IF et on tente d'executer la suppression
        if($categorie)
        {
            // Nous avons une relation entre la table Article et Category et une contrainte d'intégrité en RESTRICT
            // Donc ne pourrons pas supprimer la catégorie si 1 article lui est toujours associé
            // getArticles() de l'entité Category retourne tout les articles associés à la catégorie (relation bi-drirectionnelle)
            // Si getArticles() retourne un résultat vide, cela veut dire qu'il n'y a plus aucun article associé à la catégorie, nous pouvons dcon la supprimer
            if($categorie->getProduits()->isEmpty())
            {
                $manager->remove($categorie);
                $manager->flush();

                $this->addFlash('success', "La catégorie a été supprimée avec succès !");
            }
            else // Sinon dans tout les autres cas, des articles sont toujours associés à la catégorie, on affiche un message erreur utilisateur
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
     * @Route("/admin/categorie/new", name="admin_form_categorie")
     * @Route("/admin/categorie/edit", name="admin_form_categorie")
     */
    public function adminFormCategorie(Request $request, EntityManagerInterface $manager, Categorie $categorie = null): Response
    {
        // Si l'objet entité $category n'est pas, est null,  cela veut dire que nous sommes sur la route '/admin/category/new', que nous souhaitons créer une nouvelle catégorie, alors on entre dans la condition IF
        // Si l'objet entité $category retourne true, est bien existant en BDD, cela veut dire que nous sommes sur la route "/admin/category/{id}/edit", l'id envoyé dans l'URL a été selctionné en BDD, nous souhaitons modifier la catégorie existante
        if(!$categorie)
        {
            $categorie = new Categorie;
        }

        $formCatego = $this->createForm(CategorieFormType::class, $categorie, [
            'validation_groups' => ['categorie']
        ]); // getForm()

        dump($request);

        $formCatego->handleRequest($request); // $_POST['title'] -->  envoi dans -->setTitle($_POST['title'])

        dump($categorie);

        if($formCatego->isSubmitted() && $formCatego->isValid())
        {
            // Si l'id n'est pas connu, cela veut dire que c'est une insertion, on entre dans le IF et on définit
            if(!$categorie->getId())
                $message = "La catégorie " . $categorie->getTitre() . " a été enregistrée avec succès !";
            else 
                $message = "La catégorie " . $categorie->getTitre() . " a été modifiée avec succès !";

            $manager->persist($categorie); // on prépare et on garder en mémoire la requete INSERT
            $manager->flush();

            // // On définit un message de validation après l'execution de la requete SQL INSERT
            // $this->addFlash('success', $message);

            // Aprés l'execution de la requete INSERT, on redirige l'utilisateur vers l'affichage des catégories dans le BackOffice
            return $this->redirectToRoute('admin_categorie');
        }

        return $this->render('admin/admin_form_categorie.html.twig', [
            'formCatego' => $formCatego->createView()
        ]);  
    }

    
}
