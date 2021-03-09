<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarRepository")
 * @ORM\Table(name="cars")
 */
class Car implements Interfaces\ArrayInterface, TimestampableInterface
{
    use TimestampableTrait;

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
     * @ORM\Column(type="smallint")
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Driver", inversedBy="cars")
     * @ORM\JoinColumn()
     */
    private $driver;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="cars")
     * @ORM\JoinColumn()
     */
    private $team;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceCarDriver", mappedBy="car")
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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

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
            $raceCarDriver->setCar($this);
        }

        return $this;
    }

    public function removeRaceCarDriver(RaceCarDriver $raceCarDriver): self
    {
        if ($this->raceCarDrivers->contains($raceCarDriver)) {
            $this->cars->removeElement($raceCarDriver);
            if ($raceCarDriver->getCar() === $this) {
                $raceCarDriver->setCar(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'number' => $this->getNumber(),
        ];
    }
}
