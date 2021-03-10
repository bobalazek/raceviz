<?php

namespace App\DataFixtures;

use App\Entity\Circuit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CircuitFixtures.
 */
class CircuitFixtures extends Fixture
{
    /**
     * @var array
     */
    private $entries;

    public function __construct()
    {
        $this->entries = include __DIR__ . '/data/circuits.php';
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->entries as $entry) {
            $entity = new Circuit();
            $entity
                ->setName($entry['name'])
                ->setSlug($entry['slug'])
                ->setLocation($entry['location'])
                ->setCountryCode($entry['country_code'])
            ;

            $manager->persist($entity);

            $manager->flush();
        }
    }
}
