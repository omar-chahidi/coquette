<?php

namespace App\Form;

use App\Entity\Photo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*
            ->add('titrePhoto')
            ->add('master')
            ->add('article')
            */
            ->add('master', ChoiceType::class, [
                "label" => "Photo master",
                'choices'  => [
                    'Non' => 0,
                    'Oui' => 1,
                ],
                //'choice_value' => 'Non'
            ])
            ->add('chargementPhoto', FileType::class, [
                'label' => 'Télécharger la photo',
                'required' => false, // rendez-le facultatif pour ne pas avoir à télécharger à nouveau un fichier
                //  à chaque fois que vous modifiez les détails du Image
                'mapped' => false,   // non mappé signifie que ce champ n'est associé à aucune propriété d'entité
                'multiple' => true,  // Lorsqu'il est défini sur true, l'utilisateur pourra télécharger plusieurs fichiers en même temps.
                /*
                'constraints' => [
                    new AssertFile([
                        'maxSize' => '5120k',
                        'mimeTypes' => ['image/png', 'image/jpeg', 'image/gif'],
                        'maxSizeMessage' => 'Vous devez choisir un fichier 5 Mo maximum',
                        'mimeTypesMessage' => 'Seuls les fichiers images autorisées'
                    ])
                ]
                */

            ])
            ->add('submit', SubmitType::class, ["label" => "Valider", "attr" => ["class" => "btn btn-success"]])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
        ]);
    }
}
