<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RaceDriverRepository")
 * @ORM\Table(name="race_drivers")
 * @UniqueEntity(
 *   fields={"race", "seasonDriver"},
 *   message="This Driver was already added to this Race."
 * )
 * @Vich\Uploadable()
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Race", inversedBy="raceDrivers")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $race;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SeasonDriver", inversedBy="raceDrivers")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $seasonDriver;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\RaceDriverRaceStartingGrid", mappedBy="raceDriver", cascade={"persist"})
     * @Assert\Valid()
     */
    private $raceDriverRaceStartingGrid;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\RaceDriverRaceResult", mappedBy="raceDriver", cascade={"persist"})
     * @Assert\Valid()
     */
    private $raceDriverRaceResult;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceDriverRaceLap", mappedBy="raceDriver", cascade={"remove"})
     */
    private $raceDriverRaceLaps;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceDriverRacePitStop", mappedBy="raceDriver", cascade={"remove"})
     */
    private $raceDriverRacePitStops;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceIncidentRaceDriver", mappedBy="raceDriver")
     */
    private $raceIncidentRaceDrivers;

    public function __construct()
    {
        $this->raceDriverRaceLaps = new ArrayCollection();
        $this->raceDriverRacePitStops = new ArrayCollection();
        $this->raceIncidentRaceDrivers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getSeasonDriver() . ' @ ' . $this->getRace();
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

    public function getSeasonDriver(): ?SeasonDriver
    {
        return $this->seasonDriver;
    }

    public function setSeasonDriver(?SeasonDriver $seasonDriver): self
    {
        $this->seasonDriver = $seasonDriver;

        return $this;
    }

    public function getRaceDriverRaceStartingGrid(): ?RaceDriverRaceStartingGrid
    {
        return $this->raceDriverRaceStartingGrid;
    }

    public function setRaceDriverRaceStartingGrid(?RaceDriverRaceStartingGrid $raceDriverRaceStartingGrid): self
    {
        $this->raceDriverRaceStartingGrid = $raceDriverRaceStartingGrid;

        if ($raceDriverRaceStartingGrid) {
            $raceDriverRaceStartingGrid->setRaceDriver($this);
        }

        return $this;
    }

    public function getRaceDriverRaceResult(): ?RaceDriverRaceResult
    {
        return $this->raceDriverRaceResult;
    }

    public function setRaceDriverRaceResult(?RaceDriverRaceResult $raceDriverRaceResult): self
    {
        $this->raceDriverRaceResult = $raceDriverRaceResult;

        if ($raceDriverRaceResult) {
            $raceDriverRaceResult->setRaceDriver($this);
        }

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

    /**
     * @return Collection|RaceIncidentRaceDriver[]
     */
    public function getRaceIncidentRaceDrivers(): Collection
    {
        return $this->raceIncidentRaceDrivers;
    }

    public function addRaceIncidentRaceDriver(RaceIncidentRaceDriver $raceIncidentRaceDriver): self
    {
        if (!$this->raceIncidentRaceDrivers->contains($raceIncidentRaceDriver)) {
            $this->raceIncidentRaceDrivers[] = $raceIncidentRaceDriver;
            $raceIncidentRaceDriver->setRaceDriver($this);
        }

        return $this;
    }

    public function removeRaceIncidentRaceDriver(RaceIncidentRaceDriver $raceIncidentRaceDriver): self
    {
        if ($this->raceIncidentRaceDrivers->contains($raceIncidentRaceDriver)) {
            $this->raceIncidentRaceDrivers->removeElement($raceIncidentRaceDriver);
            if ($raceIncidentRaceDriver->getRaceDriver() === $this) {
                $raceIncidentRaceDriver->setRaceDriver(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'race_laps_count' => $this->getRaceDriverRaceLaps()->count(),
            'race_pit_stops_count' => $this->getRaceDriverRacePitStops()->count(),
            'race_driver_race_result' => $this->getRaceDriverRaceResult()
                ? $this->getRaceDriverRaceResult()->toArray()
                : null,
            'race_driver_race_starting_grid' => $this->getRaceDriverRaceStartingGrid()
                ? $this->getRaceDriverRaceStartingGrid()->toArray()
                : null,
            'season_driver' => $this->getSeasonDriver()
                ? $this->getSeasonDriver()->toArray()
                : null,
        ];
    }
}
