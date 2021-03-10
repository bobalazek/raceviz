<?php

namespace App\DataFixtures;

use App\Entity\Circuit;
use App\Entity\Race;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class RaceFixtures.
 */
class RaceFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var array
     */
    private $entries;

    public function __construct()
    {
        $this->entries = include __DIR__ . '/data/races.php';
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->entries as $entry) {
            $entity = new Race();
            $entity
                ->setName($entry['name'])
                ->setSlug($entry['slug'])
                ->setSeries($entry['series'])
                ->setLaps($entry['laps'])
                ->setStartedAt(new \DateTime($entry['debuted_at']))
                ->setCircuit(
                    $manager
                        ->getRepository(Circuit::class)
                        ->findOneBy([
                            'slug' => $entry['circuit_slug'],
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
            CircuitFixtures::class,
        ];
    }
}
