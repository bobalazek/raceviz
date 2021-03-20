<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class SeasonFixtures.
 */
class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var array
     */
    private $entries;

    public function __construct()
    {
        $this->entries = include __DIR__ . '/data/seasons.php';
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->entries as $entry) {
            $entity = new Season();
            $entity
                ->setName($entry['name'])
                ->setSlug($entry['slug'])
                ->setSeries($entry['series'])
                ->setGenericVehicle(
                    $manager
                        ->getRepository(Vehicle::class)
                        ->findOneBy([
                            'slug' => $entry['generic_vehicle_slug'],
                        ])
                )
                ->setSafetyVehicle(
                    $manager
                        ->getRepository(Vehicle::class)
                        ->findOneBy([
                            'slug' => $entry['safety_vehicle_slug'],
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
            VehicleFixtures::class,
        ];
    }
}
