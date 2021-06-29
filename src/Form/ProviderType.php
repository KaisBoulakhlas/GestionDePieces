<?php

namespace App\Form;

use App\Entity\Provider;
use Faker\Provider\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProviderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',TextType::class, [
                'label' => 'Libellé :'
            ])
            ->add('adresse',TextType::class, [
                'label' => 'Adresse :'
            ])
            ->add('city',TextType::class, [
                'label' => 'Ville :'
            ])
            ->add('country',TextType::class, [
                'label' => 'Pays :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Provider::class,
            'required' => true,
        ]);
    }
}
