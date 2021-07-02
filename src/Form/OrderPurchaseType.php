<?php

namespace App\Form;

use App\Entity\OrderPurchase;
use App\Entity\OrderPurchaseLine;
use App\Entity\Provider;
use App\Entity\WorkStation;
use App\Service\AddingQuantities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderPurchaseType extends AbstractType
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Libellé :',
                'required' => true,
            ])
            ->add('dateDeliveryPredicted', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'label' => 'Date prévue de livraison :',
                'required' => true,
            ])
            ->add('provider',EntityType::class, [
                'class' => 'App\Entity\Provider',
                'required' => true,
                'label' => 'Fournisseur :',
                'multiple' => false,
                'placeholder' => 'Sélectionner un fournisseur ...'
            ])
            ->add('dateDeliveryReal', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'label' => 'Date réelle de livraison:',
                'required' => false,
            ]);

        $builder->get('provider')->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $data = $event->getData();
            $provider = null;
            foreach($data->getOrderPurchases()->toArray() as $orderPurchase){
                $provider = $orderPurchase->getProvider();
            }
            $this->addOrderPurchaseLineField($event->getForm(),$provider);
        });

        $builder->get('provider')->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event){
            $form = $event->getForm()->getParent();
            $providerId = $event->getData();
            $provider = $this->em->find(Provider::class, $providerId);
            $this->addOrderPurchaseLineField($form, $provider);
        });
    }

    private function addOrderPurchaseLineField(FormInterface $form, ?Provider $provider)
    {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'orderPurchaseLines',
            CollectionType::class,
            null,
            [
                'entry_type' => OrderPurchaseLineType::class,
                'allow_add' => true,
                'entry_options' => [
                    'label' => false,
                    'choices' => $provider->getPieces() ?? []
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
            'data_class' => OrderPurchase::class,
        ]);
    }
}
