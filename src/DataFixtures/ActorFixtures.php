<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        '1' => [
            'firstName' => 'Andrew',
            'lastName' => 'Lilcon',
            // 'program' => 'walking-dead',
            'program' => 'Walking Dead'
        ],
        '2' => [
            'firstName' => 'Norman',
            'lastName' => 'Reedus',
            // 'program' => 'walking-dead',
            'program' => 'Walking Dead'
        ],
        '3' => [
            'firstName' => 'Lauren',
            'lastName' => 'Cohan',
            // 'program' => 'walking-dead',
            'program' => 'Walking Dead'
        ],
        '4' => [
            'firstName' => 'Danai',
            'lastName' => 'Gurira',
            // 'program' => 'walking-dead',
            'program' => 'Walking Dead'
        ]
    ];

    const PROGRAMS = [
        'Walking Dead',
        'The Haunting Of Hill House',
        'American Horror Story',
        'Love Death And Robots',
        'Penny Dreadful',
        'Fear The Walking Dead',
    ];

    public function load(ObjectManager $manager)
    {
        // $faker = Faker\Factory::create('fr_FR');
        $faker = Faker\Factory::create('us_US');
        // $programs = $this->getReference('PROGRAMS');

        foreach (self::ACTORS as $key => $data) {
            $actor = new Actor();
            $actor->setFirstname($data['firstName']);
            $actor->setLastname($data['lastName']);
            $actor->setBirthDate($faker->dateTimeBetween('-30 years', '-5 years'));
            // $actor->addProgram($this->getReference('program_'.self::PROGRAMS[random_int(0, 5)]), $actor);
            $actor->addProgram($this->getReference('program_Walking Dead'));
            $manager->persist($actor);
        }
        
        for ($i = 0; $i < 25; ++$i) {
            $actor = new Actor();
            $actor->setFirstname($faker->firstName);
            $actor->setLastname($faker->lastName);
            $actor->setBirthDate($faker->dateTimeBetween('-30 years', '-5 years'));
            $actor->addProgram($this->getReference('program_'.self::PROGRAMS[random_int(1, 5)]));
            $manager->persist($actor);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
