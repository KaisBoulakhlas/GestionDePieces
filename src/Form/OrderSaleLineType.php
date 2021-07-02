<?php

namespace App\Form;

use App\Entity\OrderLine;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderLine::class,
            'choices' => [],
        ]);
    }
}
