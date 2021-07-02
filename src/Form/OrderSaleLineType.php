<?php

namespace App\Form;

use App\Entity\OrderLine;
use Faker\Core\Number;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderSaleLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('estimateLine', EntityType::class,[
                'class' => 'App\Entity\EstimateLine',
                'label' => 'Ligne de devis :',
                'choices' => $options['choices'],
            ])
            ->add('quantity',IntegerType::class,[
                'label' => 'Quantité :',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Label :',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderLine::class,
            'choices' => [],
        ]);
    }
}
