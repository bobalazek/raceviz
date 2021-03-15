<?php

namespace App\DataFixtures;

use App\Entity\Circuit;
use App\Entity\Race;
use App\Entity\Season;
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
                ->setLaps($entry['laps'])
                ->setStartedAt(new \DateTime($entry['debuted_at']))
                ->setUrl(isset($entry['url'])
                    ? $entry['url']
                    : null)
                ->setErgastSeriesSeasonAndRound(isset($entry['ergast_series_season_and_round'])
                    ? $entry['ergast_series_season_and_round']
                    : null)
                ->setCircuit(
                    $manager
                        ->getRepository(Circuit::class)
                        ->findOneBy([
                            'slug' => $entry['circuit_slug'],
                        ])
                )
                ->setSeason(
                    $manager
                        ->getRepository(Season::class)
                        ->findOneBy([
                            'slug' => $entry['season_slug'],
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
            SeasonFixtures::class,
        ];
    }
}
