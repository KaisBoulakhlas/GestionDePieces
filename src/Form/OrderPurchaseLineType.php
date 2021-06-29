<?php

namespace App\Form;

use App\Entity\OrderPurchaseLine;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderPurchaseLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('piece', EntityType::class, [
                'class' => 'App\Entity\Piece',
                'label' => "Piece :",
                'multiple' => false,
                'query_builder' => function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('e')
                        ->where('e.type IN (:type)')
                        ->setParameter('type', ['Matière première','Achetée']);
                }
            ])
            ->add('quantity',IntegerType::class,[
                'label' => 'Quantité :',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderPurchaseLine::class,
        ]);
    }
}
