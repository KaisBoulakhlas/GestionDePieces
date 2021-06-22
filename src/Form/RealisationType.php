<?php

namespace App\Form;

use App\Entity\Machine;
use App\Entity\Realisation;
use App\Entity\User;
use App\Entity\WorkStation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RealisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, array(
                'label' => 'Libellé :',
                'required' => true,
            ))
            ->add('time',TimeType::class, array(
                'widget' => 'single_text',
                'label' => 'Durée :',
                'with_seconds' => true,
            ))
            ->add('userWorkStation', EntityType::class, [
                'label' => 'Ouvrier :',
                'placeholder' => 'Sélectionner un utilisateur :',
                'class' => 'App\Entity\User',
                'multiple' => false,
                'mapped' => false,
                'required' => false
            ]);

        $builder->get('userWorkStation')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addWorkStationField($form->getParent(), $form->getData());
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();

                /* @var $machine Machine */
                $machine = $data->getMachine();
                $form = $event->getForm();

                if ($machine) {
                    // On récupère le poste de travail  et l'utilisateur
                    $workstation = $machine->getWorkStation();
                    $user = $workstation->getUserWorkstation();

                    // On crée les 2 champs supplémentaires
                    $this->addWorkStationField($form, $user);
                    $this->addMachineField($form, $workstation);
                    // On set les données
                    $form->get('userWorkStation')->setData($user);
                    $form->get('workstation')->setData($workstation);
                } else {
                    // On crée les 2 champs en les laissant vide (champs utilisé pour le JavaScript)
                    $this->addWorkStationField($form, null);
                    $this->addMachineField($form, null);
                }
            }
        );
    }

    private function addWorkStationField(FormInterface $form, ?User $user)
    {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'workstation',
            EntityType::class,
            null,
            [
                'class' => 'App\Entity\WorkStation',
                'placeholder' => $user ? 'Sélectionnez votre poste de travail ...' : 'Sélectionnez un utilisateur ...',
                'mapped' => true,
                'required' => false,
                'auto_initialize' => false,
                'label' => 'Poste de travail :',
                'choices' => $user ? $user->getWorkStations() : []
            ]
        );

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addMachineField($form->getParent(), $form->getData());
            }
        );
        $form->add($builder->getForm());
    }

    private function addMachineField(FormInterface $form, ?WorkStation $workStation)
    {
        $form->add('machine', EntityType::class, [
            'class' => 'App\Entity\Machine',
            'label' => 'Machine :',
            'placeholder' => $workStation ? 'Sélectionnez une machine ...' : 'Sélectionnez un poste de travail ...',
            'choices' => $workStation ? $workStation->getMachines() : []
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Realisation::class,
        ]);
    }
}
