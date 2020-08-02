<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomAdresse', TextType::class)
            ->add('prenomAdresse', TextType::class)
            ->add('telephoneAdresse', TelType::class)
            ->add('adresse', TextareaType::class)
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nomVille'
            ])
            //->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
