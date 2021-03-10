<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RaceCarDriverRepository")
 * @ORM\Table(name="race_car_drivers")
 */
class RaceCarDriver implements Interfaces\ArrayInterface, TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Race", inversedBy="raceCarDrivers")
     * @ORM\JoinColumn()
     */
    private $race;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Car", inversedBy="raceCarDrivers")
     * @ORM\JoinColumn()
     */
    private $car;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Driver", inversedBy="raceCarDrivers")
     * @ORM\JoinColumn()
     */
    private $driver;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceCarDriverRaceStartingGridPosition", mappedBy="raceCarDriver")
     */
    private $raceCarDriverRaceStartingGridPositions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceCarDriverRaceLapTime", mappedBy="raceCarDriver")
     */
    private $raceCarDriverRaceLapTimes;

    public function __construct()
    {
        $this->RaceCarDriverRaceStartingGridPositions = new ArrayCollection();
        $this->raceCarDriverRaceLapTimes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getDriver() . ' @ ' . $this->getRace();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRace(): ?Race
    {
        return $this->race;
    }

    public function setRace(?Race $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

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

    /**
     * @return Collection|RaceCarDriverRaceStartingGridPosition[]
     */
    public function getRaceCarDriverRaceStartingGridPositions(): Collection
    {
        return $this->RaceCarDriverRaceStartingGridPositions;
    }

    public function addRaceCarDriverRaceStartingGridPosition(RaceCarDriverRaceStartingGridPosition $raceCarDriverRaceStartingGridPosition): self
    {
        if (!$this->RaceCarDriverRaceStartingGridPositions->contains($raceCarDriverRaceStartingGridPosition)) {
            $this->RaceCarDriverRaceStartingGridPositions[] = $raceCarDriverRaceStartingGridPosition;
            $raceCarDriverRaceStartingGridPosition->setRaceCarDriver($this);
        }

        return $this;
    }

    public function removeRaceCarDriverRaceStartingGridPosition(RaceCarDriverRaceStartingGridPosition $raceCarDriverRaceStartingGridPosition): self
    {
        if ($this->RaceCarDriverRaceStartingGridPositions->contains($raceCarDriverRaceStartingGridPosition)) {
            $this->RaceCarDriverRaceStartingGridPositions->removeElement($raceCarDriverRaceStartingGridPosition);
            if ($raceCarDriverRaceStartingGridPosition->getRaceCarDriver() === $this) {
                $raceCarDriverRaceStartingGridPosition->setRaceCarDriver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RaceCarDriverRaceLapTime[]
     */
    public function getRaceCarDriverRaceLapTimes(): Collection
    {
        return $this->raceCarDriverRaceLapTimes;
    }

    public function addRaceCarDriverRaceLapTime(RaceCarDriverRaceLapTime $raceCarDriverRaceLapTime): self
    {
        if (!$this->raceCarDriverRaceLapTimes->contains($raceCarDriverRaceLapTime)) {
            $this->raceCarDriverRaceLapTimes[] = $raceCarDriverRaceLapTime;
            $raceCarDriverRaceLapTime->setRaceCarDriver($this);
        }

        return $this;
    }

    public function removeRaceCarDriverRaceLapTime(RaceCarDriverRaceLapTime $raceCarDriverRaceLapTime): self
    {
        if ($this->raceCarDriverRaceLapTimes->contains($raceCarDriverRaceLapTime)) {
            $this->raceCarDriverRaceLapTimes->removeElement($raceCarDriverRaceLapTime);
            if ($raceCarDriverRaceLapTime->getRaceCarDriver() === $this) {
                $raceCarDriverRaceLapTime->setRaceCarDriver(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
        ];
    }
}
