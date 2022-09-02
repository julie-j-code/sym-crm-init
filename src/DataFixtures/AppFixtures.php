<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->faker = Factory::create('fr_FR');
        $this->passwordEncoder=$passwordEncoder;
    }
    
    public function load(ObjectManager $manager): void
    {

        $admin = new Users();
        $admin->setFirstName('Julie')
            ->setLastName('Jeannet')
            ->SetEmail('jeannet.julie@gmail.com')
            ->setAddress($this->faker->address())
            ->setCp($this->faker->postcode())
            ->setCity($this->faker->city())
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->hashPassword($admin, 'admin'))
            ->setTel('0667001438');

        $users[] = $admin;
        $manager->persist($admin);
        
        for ($i=1; $i <= 17; $i++) { 
            $user = new Users();
            $user->setFirstName($this->faker->firstName())
            ->setLastName($this->faker->lastName())
            ->setEmail($this->faker->email())
            ->setAddress($this->faker->address())
            ->setCp($this->faker->postcode())
            ->setCity($this->faker->city())
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordEncoder->hashPassword($user, 'password'))
            ->setTel($this->faker->mobileNumber());

            $users[] = $user;
            $manager->persist($user);
        }


        $manager->flush();
    }
}
