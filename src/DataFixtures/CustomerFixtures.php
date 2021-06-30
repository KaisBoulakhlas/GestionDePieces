<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for($i = 0; $i <= 5; $i++){
            $customer = new Customer();
            $customer->setName($faker->lastName);
            $customer->setFirstname($faker->firstName);
            $customer->setEmail($faker->unique()->email);
            $customer->setCity($faker->city);
            $customer->setAdress($faker->address);
            $customer->setPhone($faker->phoneNumber);
            $customer->setPostalCode(str_replace(' ', '', $faker->postcode));
            $this->setReference('customer_'.$i,$customer);
            $manager->persist($customer);
        }

        $manager->flush();
    }
}
