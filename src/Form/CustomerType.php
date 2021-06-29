<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label' => "Nom :"
            ])
            ->add('firstname',TextType::class,[
                'label' => "Prénom :"
            ])
            ->add('email',TextType::class,[
                'label' => "Email :"
            ])
            ->add('adress',TextType::class,[
                'label' => "Adresse :"
            ])
            ->add('phone',TextType::class,[
                'label' => "Téléphone :"
            ])
            ->add('city',TextType::class,[
                'label' => "Ville :"
            ])
            ->add('postalCode',TextType::class,[
                'label' => "Code postal :"
            ])



        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'required' => true,
        ]);
    }
}
