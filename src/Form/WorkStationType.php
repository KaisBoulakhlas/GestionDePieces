<?php

namespace App\Form;

use App\Entity\WorkStation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkStationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, array(
                'label' => 'Libellé :',
            ))
            ->add('users',EntityType::class, array(
                'class' => 'App\Entity\User',
                'label' => 'Utilisateur:',
                'placeholder' => 'Sélectionner un utilisateur ...',
                'required' => true,
                'multiple' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WorkStation::class,
        ]);
    }
}
