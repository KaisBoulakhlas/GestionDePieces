<?php

namespace App\Form;

use App\Entity\Piece;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PieceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, array(
                'label' => 'Libellé :',
                'required' => true,
            ))
            ->add('quantity', IntegerType::class, array(
                'label' => 'Quantité :',
                'required' => true,
            ))
            ->add('price', IntegerType::class, array(
                'label' => 'Prix :',
            ))
            ->add('type', ChoiceType::class, array(
                'label' => 'Type :',
                'required' => 'true',
                'placeholder' => 'Selectionner un type de pièce ...',
                'choices'  => [
                    'Matière première' => 'Matière première',
                    'Achetée' => 'Achetée',
                    'Intermédiaire' => 'Intermédiaire',
                    'Livrable' => 'Livrable',
                ],
            ))
            ->add('priceCatalogue',IntegerType::class, array(
                'label' => 'Prix d\'achat :',
            ))
            ->add('range', EntityType::class, array(
                'label' => 'Gamme de fabrication :',
                'class' => 'App\Entity\Range',
                'placeholder' => 'Sélectionner une gamme de fabrication ...',
                'multiple' => false
            ))
            ->add('provider', EntityType::class, array(
                'label' => 'Fournisseur :',
                'class' => 'App\Entity\Provider',

                'placeholder' => 'Sélectionner un fournisseur ...',
                'multiple' => false
            ))
            ->add('piecesChildren', EntityType::class,array(
                'label' => 'Pièces composantes :',
                'class' => 'App\Entity\Piece',
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
                'query_builder' => function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('e')
                        ->where('e.type != :type')
                        ->setParameter('type', 'Livrable');
                }
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Piece::class,
            'required' => false,
        ]);
    }
}
