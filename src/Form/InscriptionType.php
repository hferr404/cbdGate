<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', TextType::class, [
            'required' => false
        ])
        ->add('username', TextType::class, [
            'required' => false])
        ->add('password', PasswordType::class, [
            'required' => false])
        ->add('confirm_password', PasswordType::class, [
            'required' => false])
        ->add('avatar',FileType::class,[
            "label" => "Votre photo de profil",
            "mapped" => false,
            "required" => false,
            "constraints" => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => [
                        'image/jpg',
                        'image/png',
                        'image/jpeg'
                    ],
                    'mimeTypesMessage' => 'Extensions acceptées: jpeg/jpg/png'
                ])
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
