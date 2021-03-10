<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

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
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $series;

    /**
     * @ORM\Column(type="smallint")
     */
    private $laps;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Circuit", inversedBy="races")
     * @ORM\JoinColumn()
     */
    private $circuit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceCarDriver", mappedBy="race")
     */
    private $raceCarDrivers;

    public function __construct()
    {
        $this->raceCarDrivers = new ArrayCollection();
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSeries(): ?string
    {
        return $this->series;
    }

    public function setSeries(string $series): self
    {
        $this->series = $series;

        return $this;
    }

    public function getLaps(): ?int
    {
        return $this->laps;
    }

    public function setLaps(string $laps): self
    {
        $this->laps = $laps;

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

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeInterface $startedAt): self
    {
        $this->startedAt = $startedAt;

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
     * @return Collection|Car[]
     */
    public function getRaceCarDrivers(): Collection
    {
        return $this->raceCarDrivers;
    }

    public function addRaceCarDriver(RaceCarDriver $raceCarDriver): self
    {
        if (!$this->raceCarDrivers->contains($raceCarDriver)) {
            $this->raceCarDrivers[] = $raceCarDriver;
            $raceCarDriver->setRace($this);
        }

        return $this;
    }

    public function removeRaceCarDriver(RaceCarDriver $raceCarDriver): self
    {
        if ($this->raceCarDrivers->contains($raceCarDriver)) {
            $this->raceCarDrivers->removeElement($raceCarDriver);
            if ($raceCarDriver->getRace() === $this) {
                $raceCarDriver->setRace(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'series' => $this->getSeries(),
            'laps' => $this->getLaps(),
            'url' => $this->getUrl(),
            'started_at' => $this->getStartedAt()->format('Y-m-d'),
        ];
    }
}
