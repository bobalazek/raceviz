<?php

namespace App\DataFixtures;

use App\Entity\Driver;
use App\Entity\Season;
use App\Entity\SeasonDriver;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class SeasonDriverFixtures.
 */
class SeasonDriverFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var array
     */
    private $entries;

    public function __construct()
    {
        $this->entries = include __DIR__ . '/data/season_drivers.php';
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->entries as $entry) {
            $entity = new SeasonDriver();
            $entity
                ->setNumber($entry['number'])
                ->setSeason(
                    $manager
                        ->getRepository(Season::class)
                        ->findOneBy([
                            'slug' => $entry['season_slug'],
                        ])
                )
                ->setDriver(
                    $manager
                        ->getRepository(Driver::class)
                        ->findOneBy([
                            'slug' => $entry['driver_slug'],
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
            SeasonFixtures::class,
            DriverFixtures::class,
        ];
    }
}
