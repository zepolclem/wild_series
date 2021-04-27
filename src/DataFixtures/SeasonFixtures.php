<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Season;
use App\Service\Slugify as ServiceSlugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        'Walking Dead',
        'The Haunting Of Hill House',
        'American Horror Story',
        'Love Death And Robots',
        'Penny Dreadful',
        'Fear The Walking Dead',
    ];

    private $slugify;

    public function __construct(ServiceSlugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('us_US');

        foreach (self::PROGRAMS as $title) {
            for ($i = 1; $i < rand(2, 10); ++$i) {
                $season = new Season();
                $season->setNumber($i);
                $season->setDescription($faker->paragraph(3));
                $season->setYear(rand(2000, 2021));
                $season->setProgram($this->getReference('program_'.$title));
                $manager->persist($season);

                for ($j = 1; $j < rand(4, 20); ++$j) {
                    $episode = new Episode();
                    $episode->setNumber($j);
                    $episode->setSeason($season);
                    $episode->setTitle($faker->words(3, true));
                    $episode->setSlug($this->slugify->generate(($episode->getTitle())));
                    $episode->setSynopsis($faker->paragraph);
                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
