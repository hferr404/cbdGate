<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Boutiques;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class FormProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('prix')
            ->add('contenu')
            ->add('image',FileType::class,[
                "label" => "Photo du produit",
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
            ->add('categories', EntityType::class, [
                "class" => Categorie::class,
                "choice_label" => "titre"
           ])
           ->add('boutiques', EntityType::class, [
            "class" => Boutiques::class,
            "choice_label" => "titre"
       ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class
        ]);
    }
}
