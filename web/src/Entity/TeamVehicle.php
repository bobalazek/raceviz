<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamVehicleRepository")
 * @ORM\Table(name="team_vehicles")
 */
class TeamVehicle implements Interfaces\ArrayInterface, TimestampableInterface
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
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Driver", inversedBy="teamVehicles")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $driver;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="teamVehicles")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $team;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceDriver", mappedBy="teamVehicle")
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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
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
            $raceDriver->setTeamVehicle($this);
        }

        return $this;
    }

    public function removeRaceDriver(RaceDriver $raceDriver): self
    {
        if ($this->raceDrivers->contains($raceDriver)) {
            $this->raceDrivers->removeElement($raceDriver);
            if ($raceDriver->getTeamVehicle() === $this) {
                $raceDriver->setTeamVehicle(null);
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
