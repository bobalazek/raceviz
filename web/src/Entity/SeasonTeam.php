<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeasonTeamRepository")
 * @ORM\Table(name="season_teams")
 * @UniqueEntity(
 *   fields={"season", "team"},
 *   message="This Season Team was already added"
 * )
 */
class SeasonTeam implements Interfaces\ArrayInterface, TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Season", inversedBy="seasonTeams")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $season;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="seasonTeams")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $team;

    public function __toString()
    {
        return $this->getSeason() . ' @ ' . $this->getTeam();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
        ];
    }
}
