<?php

namespace App\Form;

use App\Entity\Variante;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VarianteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taille')
            ->add('couleur')
            ->add('stocke', IntegerType::class, [
                "attr" => ["min" => "1" , "max" => "1000000"]
            ])
            //->add('article')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Variante::class,
        ]);
    }
}
