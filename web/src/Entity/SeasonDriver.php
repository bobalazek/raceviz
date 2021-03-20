<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeasonDriverRepository")
 * @ORM\Table(name="season_drivers")
 * @UniqueEntity(
 *   fields={"season", "driver", "team"},
 *   message="This Season Driver for this Team was already added"
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
     * @ORM\Column(type="string", length=8)
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @ORM\Column(type="boolean")
     */
    private $temporary = false;

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
     * @Assert\NotBlank()
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicle")
     */
    private $vehicle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceDriver", mappedBy="seasonDriver")
     */
    private $raceDrivers;

    public function __construct()
    {
        $this->raceDrivers = new ArrayCollection();
    }

    public function __toString()
    {
        $season = $this->getSeason();
        $driver = $this->getDriver();
        $team = $this->getTeam();

        return $season . ' @ ' . $driver . ' (' . $team . ')';
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getTemporary(): bool
    {
        return $this->temporary;
    }

    public function isTemporary(): bool
    {
        return $this->getTemporary();
    }

    public function setTemporary(bool $temporary): self
    {
        $this->temporary = $temporary;

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

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * @return Collection|RaceDriver[]
     */
    public function getRaceDrivers(): Collection
    {
        return $this->raceDrivers;
    }

    public function addRaceDriver(RaceDriver $raceDriver): self
    {
        if (!$this->raceDrivers->contains($raceDriver)) {
            $this->raceDrivers[] = $raceDriver;
            $raceDriver->setSeasonDriver($this);
        }

        return $this;
    }

    public function removeRaceDriver(RaceDriver $raceDriver): self
    {
        if ($this->raceDrivers->contains($raceDriver)) {
            $this->raceDrivers->removeElement($raceDriver);
            if ($raceDriver->getSeasonDriver() === $this) {
                $raceDriver->setSeasonDriver(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'number' => $this->getNumber(),
            'code' => $this->getCode(),
            'temporary' => $this->getTemporary(),
            'season' => $this->getSeason()->toArray(),
            'driver' => $this->getDriver()->toArray(),
            'team' => $this->getTeam()->toArray(),
        ];
    }
}
