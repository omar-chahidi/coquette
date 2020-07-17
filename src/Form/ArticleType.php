<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Domaine;
use App\Entity\Marque;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            //->add('createdAt')
            ->add('prix', MoneyType::class, [
                'currency' => 'EUR'
            ])
            ->add('remise', IntegerType::class, [
                "label" => "Remise en %",
                "attr" => [
                    "min" => "0" ,
                    "max" => "90"
                ]
            ])
            ->add('marque', EntityType::class, [
                'class' =>Marque::class,
                'choice_label' => 'titre'
            ])
            ->add('categorie', EntityType::class, [
                'class' =>Categorie::class,
                'choice_label' => 'titre'
            ])
            ->add('domaine', EntityType::class, [
                'class' =>Domaine::class,
                'choice_label' => 'titre'
            ])
            ->add('description')
            ->add('motRecherche')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
