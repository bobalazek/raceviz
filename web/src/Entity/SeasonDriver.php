<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeasonDriverRepository")
 * @ORM\Table(name="season_drivers")
 * @UniqueEntity(
 *   fields={"season", "driver"},
 *   message="This Season Driver was already added"
 * )
 */
class SeasonDriver implements Interfaces\ArrayInterface, TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Season", inversedBy="seasonDrivers")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $season;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Driver", inversedBy="seasonDrivers")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $driver;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="seasonDrivers")
     * @ORM\JoinColumn()
     */
    private $team;

    public function __toString()
    {
        $season = $this->getSeason();
        $driver = $this->getDriver();
        $team = $this->getTeam();

        return $season . ' @ ' . $driver . ($team ? ' (' . $team . ')' : '');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
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

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(?Driver $driver): self
    {
        $this->driver = $driver;

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
            'number' => $this->getNumber(),
            'driver' => $this->getDriver()
                ? $this->getDriver()->toArray()
                : null,
            'team' => $this->getTeam()
                ? $this->getTeam()->toArray()
                : null,
        ];
    }
}
