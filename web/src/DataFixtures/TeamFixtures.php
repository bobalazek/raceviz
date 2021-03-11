<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TeamFixtures.
 */
class TeamFixtures extends Fixture
{
    /**
     * @var array
     */
    private $entries;

    public function __construct()
    {
        $this->entries = include __DIR__ . '/data/teams.php';
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->entries as $entry) {
            $entity = new Team();
            $entity
                ->setName($entry['name'])
                ->setSlug($entry['slug'])
                ->setSeries($entry['series'])
                ->setLocation($entry['location'])
                ->setCountryCode($entry['country_code'])
                ->setUrl($entry['url'])
                ->setDebutedAt(new \DateTime($entry['debuted_at']))
                ->setDefunctedAt(isset($entry['defuncted_at'])
                    ? new \DateTime($entry['defuncted_at'])
                    : null)
            ;

            $manager->persist($entity);

            $manager->flush();
        }
    }
}
