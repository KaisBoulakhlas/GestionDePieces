<?php

namespace App\Form;

use App\Entity\User;
use Faker\Provider\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('email',EmailType::class,[
                'label' => 'Email :',
            ])
            ->add('plainPassword', TextType::class, [
                'label' => 'Mot de passe :'
            ])
            ->add('username', TextType::class, [
                'label' => 'Pseudonyme :'
            ])
            ->add('roles',ChoiceType::class, [
                'label' => 'Rôles :',
                'placeholder' => 'Selectionner un rôle ...',
                'choices'  => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Ouvrier' => 'ROLE_OUVRIER',
                    'Commercial' => 'ROLE_COMMERCIAL',
                ],
            ])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'roles' => null,
        ]);
    }
}
