<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\InscriptionType;
use App\Form\InscriptMembreType;
use Doctrine\ORM\EntityManagerInterface;
use App\Notification\InscriptionNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_inscription")
     */

    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, InscriptionNotification $inscriptionNotification, SluggerInterface $slugger)
     {
    $membre = new Membre(); 
  
    $form = $this->createForm(InscriptionType::class, $membre); 

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {

        /** @var UploadedFile $imageFile */    
        $imageFile = $form->get('avatar')->getData();

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

            $membre->setAvatar($newFileName);
        }

        $inscriptionNotification->notify($membre);

    $hash = $encoder->encodePassword($membre, $membre->getPassword());

    $membre->setPassword($hash); 

    $membre->setRoles(["ROLE_USER"]);

    $manager->persist($membre);
   
    $manager->flush(); 

    return $this->redirectToRoute('security_login');
    }
   

    return $this->render('security/inscription.html.twig' ,[
        'form' => $form->createView()
    ]);
  
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authentication):Response
    {
        $erreur = $authentication->getLastAuthenticationError();
        $lastUserName = $authentication->getLastUsername();
        return $this->render('security/connexion.html.twig', [
            'erreur' => $erreur, 
            'lastUserName' => $lastUserName 
        ]);
    }

    /**
     * @Route("\deconnexion", name="security_logout")
     */
    public function logout(){

    }
    /**
     * @Route("/profil", name="profil")
     */
    public function profil(AuthenticationUtils $profil)
    {
        
        return $this->render('security/profil.html.twig');
    }

}
