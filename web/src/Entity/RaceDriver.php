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
 * @ORM\Entity(repositoryClass="App\Repository\RaceDriverRepository")
 * @ORM\Table(name="race_drivers")
 * @UniqueEntity(
 *   fields={"race", "driver"},
 *   message="This Driver was already added to this Race."
 * )
 */
class RaceDriver implements Interfaces\ArrayInterface, TimestampableInterface
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
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $raceStartingGridTyres;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Race", inversedBy="raceDrivers")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $race;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="raceDrivers")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Driver", inversedBy="raceDrivers")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $driver;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceDriverRaceLap", mappedBy="raceDriver")
     */
    private $raceDriverRaceLaps;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceDriverRacePitStop", mappedBy="raceDriver")
     */
    private $raceDriverRacePitStops;

    public function __construct()
    {
        $this->raceDriverRaceLaps = new ArrayCollection();
        $this->raceDriverRacePitStops = new ArrayCollection();
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

    public function getRaceStartingGridTyres(): ?string
    {
        return $this->raceStartingGridTyres;
    }

    public function setRaceStartingGridTyres(?string $raceStartingGridTyres): self
    {
        $this->raceStartingGridTyres = $raceStartingGridTyres;

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

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

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
     * @return Collection|RaceDriverRaceLap[]
     */
    public function getRaceDriverRaceLaps(): Collection
    {
        return $this->raceDriverRaceLaps;
    }

    public function addRaceDriverRaceLap(RaceDriverRaceLap $raceDriverRaceLap): self
    {
        if (!$this->raceDriverRaceLaps->contains($raceDriverRaceLap)) {
            $this->raceDriverRaceLaps[] = $raceDriverRaceLap;
            $raceDriverRaceLap->setRaceDriver($this);
        }

        return $this;
    }

    public function removeRaceDriverRaceLap(RaceDriverRaceLap $raceDriverRaceLap): self
    {
        if ($this->raceDriverRaceLaps->contains($raceDriverRaceLap)) {
            $this->raceDriverRaceLaps->removeElement($raceDriverRaceLap);
            if ($raceDriverRaceLap->getRaceDriver() === $this) {
                $raceDriverRaceLap->setRaceDriver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RaceDriverRacePitStop[]
     */
    public function getRaceDriverRacePitStops(): Collection
    {
        return $this->raceDriverRacePitStops;
    }

    public function addRaceDriverRacePitStop(RaceDriverRacePitStop $raceDriverRacePitStop): self
    {
        if (!$this->raceDriverRacePitStops->contains($raceDriverRacePitStop)) {
            $this->raceDriverRacePitStops[] = $raceDriverRacePitStop;
            $raceDriverRacePitStop->setRaceDriver($this);
        }

        return $this;
    }

    public function removeRaceDriverRacePitStop(RaceDriverRacePitStop $raceDriverRacePitStop): self
    {
        if ($this->raceDriverRacePitStops->contains($raceDriverRacePitStop)) {
            $this->raceDriverRacePitStops->removeElement($raceDriverRacePitStop);
            if ($raceDriverRacePitStop->getRaceDriver() === $this) {
                $raceDriverRacePitStop->setRaceDriver(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'team' => $this->getTeam()
                ? $this->getTeam()->toArray()
                : null,
            'driver' => $this->getDriver()
                ? $this->getDriver()->toArray()
                : null,
        ];
    }
}
