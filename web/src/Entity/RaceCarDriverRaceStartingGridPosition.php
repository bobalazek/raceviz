<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CircuitRepository")
 * @ORM\Table(name="race_car_driver_race_starting_grid_positions")
 */
class RaceCarDriverRaceStartingGridPosition implements Interfaces\ArrayInterface, TimestampableInterface
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
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RaceCarDriver", inversedBy="raceCarDriverRaceStartingGridPositions")
     * @ORM\JoinColumn()
     */
    private $raceCarDriver;

    public function __toString()
    {
        $race = $this->getRaceCarDriver()->getRace();
        $driver = $this->getRaceCarDriver()->getDriver();
        $position = $this->getPosition();

        return $driver . ' @ ' . $race . ' ( ' . $position . ' position)';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getRaceCarDriver(): ?RaceCarDriver
    {
        return $this->raceCarDriver;
    }

    public function setRaceCarDriver(?RaceCarDriver $raceCarDriver): self
    {
        $this->raceCarDriver = $raceCarDriver;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'position' => $this->getPosition(),
        ];
    }
}
