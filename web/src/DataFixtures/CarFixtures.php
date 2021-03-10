<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\Driver;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CarFixtures.
 */
class CarFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var array
     */
    private $entries;

    public function __construct()
    {
        $this->entries = include __DIR__ . '/data/cars.php';
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->entries as $entry) {
            $entity = new Car();
            $entity
                ->setName($entry['name'])
                ->setSlug($entry['slug'])
                ->setNumber($entry['number'])
                ->setDriver(
                    $manager
                        ->getRepository(Driver::class)
                        ->findOneBy([
                            'slug' => $entry['driver_slug'],
                        ])
                )
                ->setTeam(
                    $manager
                        ->getRepository(Team::class)
                        ->findOneBy([
                            'slug' => $entry['team_slug'],
                        ])
                )
            ;

            $manager->persist($entity);

            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            DriverFixtures::class,
            TeamFixtures::class,
        ];
    }
}
