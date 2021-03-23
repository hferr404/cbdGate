<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\FormProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }




    

    // public function create(Produit $produit = null, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    // {
      

    //     if(!$produit)  
    //     {  
    //         $produit = new Produit;
    //     }  

    //     $produit->setTitre('CBD AUDI KUSH')
    //               ->setContenu('SE FUME');  

                

    //   $form = $this->createForm(FormProduitType::class, $produit);

    //   $form->handleRequest($request);

     

    // //   if($form->isSubmitted() && $form->isValid())
    // //   {
    // //     //   /** @var UploadedFile */    
    // //     //   $imageFile = $form->get('image')->getData();

 

    // //     //   if($imageFile)
    // //     //   {
             
    // //     //       $originalFileName = pathinfo($imageFile->geClientOriginalName(), PATHINFO_FILENAME);
    // //     //       dump($originalFileName);

    // //     //       $safeFileName = $slugger->slug($originalFileName);
    // //     //       dump($originalFileName);

    // //     //       $newFileName = $safeFileName . '-' . uniqid() . '.' . $imageFile->guessExtention();

    // //     //       try
    // //     //       {
    // //     //           $imageFile->move(
    // //     //               $this->getParameter('image_directory')
    // //     //           );
    // //     //       }
    // //     //       catch(FileException $e)
    // //     //       {


    // //           $articleCr->setImgae($newFileName);
    // //       }
          
    //       if(!$produit->getId())
    //       {
    //           $produit->setDateCreation(new \DateTime);
    //       }                                

    //       $manager->persist($produit);
    //       $manager->flush();

    //     // //   return $this->redirectToRoute('produit_show', [
    //     // //       "id" => $produit->getId()
    //     //   ]);
      

    //     return $this->render("admin/ajout_produit.html.twig", [
    //         'formProduit' => $form->createView(),
    //         'editMode' => $produit->getId()
    //     ]);
    // }


    /**
     * @Route("/admin/edit-produit", name="admin_edit_produit")
     */

    public function adminEditArticle(Produit $produit, Request $request, EntityManagerInterface $manager)
    {
        $formProduit = $this->createForm(FormProduitType::class, $produit);

        $formProduit->handleRequest($request);

        if ($formProduit->isSubmitted() && $formProduit->isValid()) {
            $manager->persist($produit);
            $manager->flush();

            // $this->addFlash('success', "Le produit " . $produit->getId() . " a bien été modifié");  

            return $this->redirectToRoute('admin_produit');
        }

        return $this->render('admin/admin_edit_produit.html.twig', [
            //   "idProduit" => $produit->getId(),
              "formProduit" => $formProduit->createView()
          ]);
    }

    
}
