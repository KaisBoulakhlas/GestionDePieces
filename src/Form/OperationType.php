<?php

namespace App\Form;

use App\Entity\Operation;
use App\Entity\WorkStation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class OperationType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',TextType::class, [
                'label' => 'Libellé : ',
            ])
            ->add('time',TimeType::class, [
                'label' => 'Durée : ',
                'widget' => 'single_text',
                'with_seconds' => true
            ])
            ->add('ranges', EntityType::class, [
                'class' => 'App\Entity\Range',
                'multiple' => true,
                'required' => true,
                'label' => 'Gamme :'

            ])
            ->add('workStation', EntityType::class, [
                'label' => 'Poste de travail : ',
                'placeholder' => 'Sélectionner un poste de travail ...',
                'class'         => 'App\Entity\WorkStation',
                'multiple'      => false,
            ]);


        $formModifier = function (FormInterface $form, WorkStation $workStation = null) {
            $machines = null === $workStation ? array() : $workStation->getMachines();

            $form->add('machine', EntityType::class, array(
                'class' => 'App\Entity\Machine',
                'placeholder' => 'Sélectionner une machine ...',
                'choices' => $machines,
                'label' => 'Machines :',
                'multiple'      => false,
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $formModifier($event->getForm(),$data->getWorkStation());
            }
        );

        $builder->get('workStation')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $workStation = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $workStation);
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                $machine = $data->getMachine();
                if ($machine != null){
                    $form->get('workStation')->setData($machine->getWorkStation());
                    $form->add('machine', EntityType::class, array(
                        'class' => 'App\Entity\Machine',
                        'placeholder' => 'Sélectionner une machine ...',
                        'choices' => $machine->getWorkStation()->getMachines(),
                        'label' => 'Machines :',
                        'multiple'      => false,
                    ));
                }

            }
        );
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
