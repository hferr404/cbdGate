<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_inscription")
     */

    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
     {
    $membre = new Membre(); 
  
    $form = $this->createForm(InscriptionType::class, $membre); 

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
    
    $hash = $encoder->encodePassword($membre, $membre->getPassword());

    $membre->setPassword($hash); 

    $membre->setRoles(["ROLE_USER"]);

    $manager->persist($membre);
   
    $manager->flush(); 
    }
   

    return $this->render('security/inscription.html.twig' ,[
        'form' => $form->createView()
    ]);
  
    }
}
