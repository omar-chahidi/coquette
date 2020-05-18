<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('username')
            ->add('password', PasswordType::class)
            ->add('nomUtilisateur')
            ->add('prenom')
            ->add('telephone')
            ->add('adresse')
            /*
            ->add('dateNaissance', BirthdayType::class, [
                'placeholder' => [
                    'day' => 'jours', 'month' => 'mois', 'year' => 'année',
                ]
            ])
            */
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                //'format' => 'dd-MM-yyyy',
            ])
            //->add('activation')
            //->add('dateAjout')
            //->add('dateDesactivation')
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nomVille'
            ])
            ->add('confirm_password', PasswordType::class) // ajouter confirmation . il faut ajouter cette variable confirm_password dans l'entité Utilisateur

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
