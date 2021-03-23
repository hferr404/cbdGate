<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    /**
     * @Route("/admin/produit", name="admin_produit")     
     * @Route("/admin/{id.}", name="admin_remove_produit")      
     * 
     */
    public function adminProduit(EntityManagerInterface $manager, Produit $produit=null, ProduitRepository $produitRepo):Response
    {
        $colonne=$manager->getClassMetadata(Produit::class)->getFieldNames();

        $produits=$produitRepo->findAll();

        if($produit)
        {
            $id=$produit->getId();
            $manager->remove($produit);
            $manager->flush();
            return $this->redirectToRoute('admin_produit');
        }
        
        return $this->render('admin/admin_produit.html.twig',[
            'colonne'=> $colonne,
            'produit'=>$produits
        ]);
    }
}
