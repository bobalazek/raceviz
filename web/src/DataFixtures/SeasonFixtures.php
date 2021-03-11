<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class SeasonFixtures.
 */
class SeasonFixtures extends Fixture
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
            ;

            $manager->persist($entity);

            $manager->flush();
        }
    }
}
