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
     */
    private $laps;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $url;

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

    public function __construct()
    {
        $this->raceDrivers = new ArrayCollection();
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
     * @return Collection|TeamVehicle[]
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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'laps' => $this->getLaps(),
            'url' => $this->getUrl(),
            'started_at' => $this->getStartedAt()->format('Y-m-d'),
        ];
    }
}
