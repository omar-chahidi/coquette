<?php

namespace App\Form;

use App\Entity\Utilisateur;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomUtilisateur')
            ->add('prenom')
            ->add('telephone')
            ->add('adresse')
            ->add('dateNaissance', BirthdayType::class, [
                'placeholder' => [
                    'day' => 'jours', 'month' => 'mois', 'year' => 'année',
                ]
            ])
            ->add('email')
            ->add('mdp', PasswordType::class)
            ->add('confirm_password',PasswordType::class) // ajouter confirmation . il faut ajouter cette variable confirm_password dans l'entité Utilisateur
            //->add('activation')
            //->add('dateAjout')
            //->add('dateDesactivation')
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nomVille'
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
