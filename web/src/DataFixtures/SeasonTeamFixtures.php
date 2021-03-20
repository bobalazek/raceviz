<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Entity\SeasonTeam;
use App\Entity\Team;
use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class SeasonTeamFixtures.
 */
class SeasonTeamFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var array
     */
    private $entries;

    public function __construct()
    {
        $this->entries = include __DIR__ . '/data/season_teams.php';
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->entries as $entry) {
            $entity = new SeasonTeam();
            $entity
                ->setSeason(
                    $manager
                        ->getRepository(Season::class)
                        ->findOneBy([
                            'slug' => $entry['season_slug'],
                        ])
                )
                ->setTeam(
                    $manager
                        ->getRepository(Team::class)
                        ->findOneBy([
                            'slug' => $entry['team_slug'],
                        ])
                )
                ->setVehicle(
                    $manager
                        ->getRepository(Vehicle::class)
                        ->findOneBy([
                            'slug' => $entry['vehicle_slug'],
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
            TeamFixtures::class,
            VehicleFixtures::class,
        ];
    }
}
