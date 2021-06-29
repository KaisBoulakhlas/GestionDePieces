<?php

namespace App\Form;

use App\Entity\Piece;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Unique;

class PieceType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference',TextType::class, [
                'label' => 'Référence :',
                'attr' => array(
                    'placeholder' => 'Ex: EPPMF220',
                ),
                'help' => 'La référence doit être en majuscule et doit contenir 5 lettres suivis par 3 chiffres .',
                'constraints' => [
                    new Regex([
                        'pattern' => '#[A-Z]{5}[0-9]{3}#',
                        'message' => 'La référence doit être en majuscule et doit contenir 5 lettres suivis par 3 chiffres .',
                    ]),
                ]
            ])
            ->add('libelle', TextType::class, array(
                'label' => 'Libellé :',
                'required' => true,

            ))
            ->add('quantity', IntegerType::class, array(
                'label' => 'Quantité :',
                'required' => true,
            ))
            ->add('price', NumberType::class, array(
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
            ->add('priceCatalogue',NumberType::class, array(
                'label' => 'Prix d\'achat :',
            ))
            ->add('provider', EntityType::class, array(
                'label' => 'Fournisseur :',
                'class' => 'App\Entity\Provider',
                'placeholder' => 'Sélectionner un fournisseur ...',
                'multiple' => false
            ))
            ->add('pieceUseds', CollectionType::class,array(
                'entry_type' => PieceUsedType::class,
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
            'data_class' => Piece::class,
            'required' => false,
            'constraints' => [
                new UniqueEntity(['fields' => ['reference'], 'message' => 'Cette référence existe déjà.']),
            ],
        ]);
    }
}
