<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RaceCarDriverRepository")
 * @ORM\Table(name="race_car_drivers")
 * @UniqueEntity(
 *   fields={"race", "car", "driver"},
 *   message="This Race Car Driver was already added"
 * )
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
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $raceStartingGridPosition;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $raceResultPosition;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $raceResultPoints;

    /**
     * @ORM\Column(type="time_with_milliseconds", nullable=true)
     */
    private $raceResultTime;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $raceResultLapsBehind;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $raceResultStatus;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $raceResultStatusNote;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Race", inversedBy="raceCarDrivers")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $race;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Car", inversedBy="raceCarDrivers")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $car;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Driver", inversedBy="raceCarDrivers")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $driver;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceCarDriverRaceLap", mappedBy="raceCarDriver")
     */
    private $raceCarDriverRaceLaps;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceCarDriverRacePitStop", mappedBy="raceCarDriver")
     */
    private $raceCarDriverRacePitStops;

    public function __construct()
    {
        $this->raceCarDriverRaceLaps = new ArrayCollection();
        $this->raceCarDriverRacePitStops = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getDriver() . ' @ ' . $this->getRace();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaceStartingGridPosition(): ?int
    {
        return $this->raceStartingGridPosition;
    }

    public function setRaceStartingGridPosition(?int $raceStartingGridPosition): self
    {
        $this->raceStartingGridPosition = $raceStartingGridPosition;

        return $this;
    }

    public function getRaceResultPosition(): ?int
    {
        return $this->raceResultPosition;
    }

    public function setRaceResultPosition(?int $raceResultPosition): self
    {
        $this->raceResultPosition = $raceResultPosition;

        return $this;
    }

    public function getRaceResultPoints(): ?int
    {
        return $this->raceResultPoints;
    }

    public function setRaceResultPoints(?int $raceResultPoints): self
    {
        $this->raceResultPoints = $raceResultPoints;

        return $this;
    }

    public function getRaceResultTime(): ?\DateTimeInterface
    {
        return $this->raceResultTime;
    }

    public function setRaceResultTime(?\DateTimeInterface $raceResultTime): self
    {
        $this->raceResultTime = $raceResultTime;

        return $this;
    }

    public function getRaceResultLapsBehind(): ?int
    {
        return $this->raceResultLapsBehind;
    }

    public function setRaceResultLapsBehind(?int $raceResultLapsBehind): self
    {
        $this->raceResultLapsBehind = $raceResultLapsBehind;

        return $this;
    }

    public function getRaceResultStatus(): ?string
    {
        return $this->raceResultStatus;
    }

    public function setRaceResultStatus(?string $raceResultStatus): self
    {
        $this->raceResultStatus = $raceResultStatus;

        return $this;
    }

    public function getRaceResultStatusNote(): ?string
    {
        return $this->raceResultStatusNote;
    }

    public function setRaceResultStatusNote(?string $raceResultStatusNote): self
    {
        $this->raceResultStatusNote = $raceResultStatusNote;

        return $this;
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
     * @return Collection|RaceCarDriverRaceLap[]
     */
    public function getRaceCarDriverRaceLaps(): Collection
    {
        return $this->raceCarDriverRaceLaps;
    }

    public function addRaceCarDriverRaceLap(RaceCarDriverRaceLap $raceCarDriverRaceLap): self
    {
        if (!$this->raceCarDriverRaceLaps->contains($raceCarDriverRaceLap)) {
            $this->raceCarDriverRaceLaps[] = $raceCarDriverRaceLap;
            $raceCarDriverRaceLap->setRaceCarDriver($this);
        }

        return $this;
    }

    public function removeRaceCarDriverRaceLap(RaceCarDriverRaceLap $raceCarDriverRaceLap): self
    {
        if ($this->raceCarDriverRaceLaps->contains($raceCarDriverRaceLap)) {
            $this->raceCarDriverRaceLaps->removeElement($raceCarDriverRaceLap);
            if ($raceCarDriverRaceLap->getRaceCarDriver() === $this) {
                $raceCarDriverRaceLap->setRaceCarDriver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RaceCarDriverRacePitStop[]
     */
    public function getRaceCarDriverRacePitStops(): Collection
    {
        return $this->raceCarDriverRacePitStops;
    }

    public function addRaceCarDriverRacePitStop(RaceCarDriverRacePitStop $raceCarDriverRacePitStop): self
    {
        if (!$this->raceCarDriverRacePitStops->contains($raceCarDriverRacePitStop)) {
            $this->raceCarDriverRacePitStops[] = $raceCarDriverRacePitStop;
            $raceCarDriverRacePitStop->setRaceCarDriver($this);
        }

        return $this;
    }

    public function removeRaceCarDriverRacePitStop(RaceCarDriverRacePitStop $raceCarDriverRacePitStop): self
    {
        if ($this->raceCarDriverRacePitStops->contains($raceCarDriverRacePitStop)) {
            $this->raceCarDriverRacePitStops->removeElement($raceCarDriverRacePitStop);
            if ($raceCarDriverRacePitStop->getRaceCarDriver() === $this) {
                $raceCarDriverRacePitStop->setRaceCarDriver(null);
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
