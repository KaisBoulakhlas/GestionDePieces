<?php


namespace App\EventListener;


use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder){

        $this->encoder = $encoder;
    }
    /**
     * @param LifecycleEventArgs $args
     * @ORM\PrePersist()
     */
    public function prePersist(LifecycleEventArgs $args) : void
    {
        /**
         * @var User $entity
         */
        $entity = $args->getEntity();
        if(true === property_exists($entity, 'password') && $entity instanceof User){
            $encoded = $this->encoder->hashPassword($entity, $entity->getPlainPassword());
            $entity->setPassword($encoded);
        }
    }


    /**
     * @param LifecycleEventArgs $args
     * @ORM\PreUpdate()
     */
    public function preUpdate(LifecycleEventArgs $args) : void
    {
        /**
         * @var User $entity
         */
        $entity = $args->getEntity();
        if(true === property_exists($entity, 'password') && $entity instanceof User){
            $encoded = $this->encoder->hashPassword($entity, $entity->getPlainPassword());
            $entity->setPassword($encoded);
        }
    }
}