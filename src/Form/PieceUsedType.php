<?php

namespace App\Form;

use App\Entity\PieceUsed;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PieceUsedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('piece', EntityType::class, [
                'class' => 'App\Entity\Piece',
                'label' => 'Pièce composante :',
                'multiple' => false,
                'query_builder' => function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('e')
                        ->where('e.type != :type')
                        ->setParameter('type', 'Livrable');
                }
            ])
            ->add('quantity',IntegerType::class, [
                'label' => 'Quantité :',
            ])



        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PieceUsed::class,
        ]);
    }
}
