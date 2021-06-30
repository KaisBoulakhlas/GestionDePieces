<?php

namespace App\Form;

use App\Entity\EstimateLine;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstimateLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity',IntegerType::class,[
                'label' => 'Quantité :',
            ])
            ->add('price',NumberType::class,[
                'label' => 'Prix :',
            ])
            ->add('piece', EntityType::class,[
                'label' => 'Pièces livrable :',
                'class' => 'App\Entity\Piece',
                'multiple' => false,
                'query_builder' => function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('e')
                        ->where('e.type IN (:type)')
                        ->setParameter('type', ['Livrable']);
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EstimateLine::class,
            'required' => true
        ]);
    }
}
