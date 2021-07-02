<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\OrderSale;
use App\Entity\Provider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderSaleType extends AbstractType
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',TextType::class,[
                'label' => 'Libellé :'
            ])
            ->add('description',TextType::class,[
                'label' => 'Description :'
            ])
            ->add('date',DateTimeType::class,[
                'label' => 'Date de création :',
                'date_widget' => 'single_text'
            ])
            ->add('customer',EntityType::class,[
                'class' => 'App\Entity\Customer',
                'placeholder' => 'Sélectionner un client ...',
                'label' => 'Client :'
            ])
        ;

        $builder->get('customer')->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event){
            $form = $event->getForm()->getParent();
            $customerId = $event->getData();
            $customer = $this->em->find(Customer::class, $customerId);
            $this->addOrderSaleLineField($form, $customer);
        });
    }


    private function addOrderSaleLineField(FormInterface $form, ?Customer $customer)
    {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'orderLines',
            CollectionType::class,
            null,
            [
                'entry_type' => OrderSaleLineType::class,
                'allow_add' => true,
                'entry_options' => [
                    'label' => false,
                    'choices' => $customer->getEstimateLines() ?? []
                ],
                'allow_delete'=> true,
                'by_reference' => false,
                'auto_initialize' => false,
            ]
        );


        $form->add($builder->getForm());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderSale::class,
        ]);
    }
}
