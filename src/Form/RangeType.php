<?php

namespace App\Form;

use App\Entity\Range;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RangeType extends AbstractType
{

    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $workers = $this->em->getRepository(User::class)->findUsersByRole('ROLE_OUVRIER');
        $builder
            ->add('libelle',TextType::class,array('label' => 'Libellé:','required' => true))
            ->add('userWorkstation',ChoiceType::class, array(
                    'choices' => $workers,
                    'label' => 'Responsable :',
                    'placeholder' => 'Sélectionner un responsable ...',
                    'choice_label' => function ($choice) { return $choice->getUsername(); },
                    'required' => true,
            ))
            ->add('piece', EntityType::class, array(
                'label' => 'Pièce fabriquée :',
                'class' => 'App\Entity\Piece',
                'placeholder' => 'Sélectionner une pièce fabriquée ...',
                'multiple' => false,
                'query_builder' => function(EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('p')
                        ->where('p.type in (:type)')
                        ->setParameter(':type',['Livrable','Intermédiaire']);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Range::class,
        ]);
    }
}
