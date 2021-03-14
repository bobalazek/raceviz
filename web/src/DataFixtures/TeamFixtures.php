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
                ->setFullName($entry['full_name'])
                ->setSlug($entry['slug'])
                ->setLocation($entry['location'])
                ->setCountryCode($entry['country_code'])
                ->setUrl(isset($entry['url'])
                    ? $entry['url']
                    : null)
                ->setColor(isset($entry['color'])
                    ? $entry['color']
                    : null)
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
