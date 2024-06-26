<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RaceRepository")
 * @ORM\Table(name="races")
 */
class Race implements Interfaces\ArrayInterface, TimestampableInterface
{
    use TimestampableTrait;
    use Traits\SlugTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\LessThan(32768)
     */
    private $laps;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\LessThan(32768)
     */
    private $lapDistance;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\LessThan(32768)
     */
    private $round;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ergastSeriesSeasonAndRound;

    /**
     * @ORM\Column(type="boolean")
     */
    private $public = false;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $startedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Season", inversedBy="races")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $season;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Circuit", inversedBy="races")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $circuit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceDriver", mappedBy="race")
     */
    private $raceDrivers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceIncident", mappedBy="race")
     */
    private $raceIncidents;

    public function __construct()
    {
        $this->raceDrivers = new ArrayCollection();
        $this->raceIncidents = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLaps(): ?int
    {
        return $this->laps;
    }

    public function setLaps(?int $laps): self
    {
        $this->laps = $laps;

        return $this;
    }

    public function getLapDistance(): ?int
    {
        return $this->lapDistance;
    }

    public function setLapDistance(?int $lapDistance): self
    {
        $this->lapDistance = $lapDistance;

        return $this;
    }

    public function getRound(): ?int
    {
        return $this->round;
    }

    public function setRound(?int $round): self
    {
        $this->round = $round;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getErgastSeriesSeasonAndRound(): ?string
    {
        return $this->ergastSeriesSeasonAndRound;
    }

    public function setErgastSeriesSeasonAndRound(?string $ergastSeriesSeasonAndRound): self
    {
        $this->ergastSeriesSeasonAndRound = $ergastSeriesSeasonAndRound;

        return $this;
    }

    public function getPublic(): bool
    {
        return $this->public;
    }

    public function isPublic(): bool
    {
        return $this->getPublic();
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeInterface $startedAt): self
    {
        $this->startedAt = $startedAt;

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

    public function getCircuit(): ?Circuit
    {
        return $this->circuit;
    }

    public function setCircuit(?Circuit $circuit): self
    {
        $this->circuit = $circuit;

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
            $raceDriver->setRace($this);
        }

        return $this;
    }

    public function removeRaceDriver(RaceDriver $raceDriver): self
    {
        if ($this->raceDrivers->contains($raceDriver)) {
            $this->raceDrivers->removeElement($raceDriver);
            if ($raceDriver->getRace() === $this) {
                $raceDriver->setRace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RaceIncident[]
     */
    public function getRaceIncidents(): Collection
    {
        return $this->raceIncidents;
    }

    public function addRaceIncident(RaceIncident $raceIncident): self
    {
        if (!$this->raceIncidents->contains($raceIncident)) {
            $this->raceIncidents[] = $raceIncident;
            $raceIncident->setRace($this);
        }

        return $this;
    }

    public function removeRaceIncident(RaceIncident $raceIncident): self
    {
        if ($this->raceIncidents->contains($raceIncident)) {
            $this->raceIncidents->removeElement($raceIncident);
            if ($raceIncident->getRace() === $this) {
                $raceIncident->setRace(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'laps' => $this->getLaps(),
            'lap_distance' => $this->getLapDistance(),
            'round' => $this->getRound(),
            'url' => $this->getUrl(),
            'ergast_series_season_and_round' => $this->getErgastSeriesSeasonAndRound(),
            'public' => $this->getPublic(),
            'started_at' => $this->getStartedAt()->format('Y-m-d'),
            'season' => $this->getSeason()->toArray(),
            'circuit' => $this->getCircuit()->toArray(),
        ];
    }
}
