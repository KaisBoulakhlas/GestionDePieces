<?php

namespace App\Form;

use App\Entity\Estimate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstimateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'label' => 'Titre :',
            ])
            ->add('deadline',DateTimeType::class,[
                'label' => 'Délai :',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
            ])
            ->add('customer', EntityType::class,[
                'label' => 'Client :',
                'placeholder' => 'Sélectionner un client ...',
                'class' => 'App\Entity\Customer',
                'multiple' => false,
            ])
            ->add('estimateLines', CollectionType::class,array(
                'entry_type' => EstimateLineType::class,
                'allow_add' => true,
                'entry_options' => ['label' => false],
                'allow_delete'=> true,
                'by_reference' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Estimate::class,
            'required' => true,
        ]);
    }
}
