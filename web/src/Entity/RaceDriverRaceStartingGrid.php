<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RaceDriverRaceStartingGridRepository")
 * @ORM\Table(name="race_driver_race_starting_grids")
 * @UniqueEntity(
 *   fields={"raceDriver"},
 *   message="This Race Driver was already added"
 * )
 */
class RaceDriverRaceStartingGrid implements Interfaces\ArrayInterface, TimestampableInterface
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
     * @Assert\NotBlank()
     */
    private $position;

    /**
     * @ORM\Column(type="time_with_milliseconds", nullable=true)
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $tyres;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\RaceDriver", inversedBy="raceDriverRaceStartingGrid")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $raceDriver;

    public function __toString()
    {
        $race = $this->getRaceDriver()->getRace();
        $driver = $this->getRaceDriver()->getDriver();
        $position = $this->getPosition();

        return $driver . ' @ ' . $race . ' (at starting grid position ' . $position . ')';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getTyres(): ?string
    {
        return $this->tyres;
    }

    public function setTyres(string $tyres): self
    {
        $this->tyres = $tyres;

        return $this;
    }

    public function getRaceDriver(): ?RaceDriver
    {
        return $this->raceDriver;
    }

    public function setRaceDriver(?RaceDriver $raceDriver): self
    {
        $this->raceDriver = $raceDriver;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'position' => $this->getPosition(),
            'time' => $this->getTime()
                ? $this->getTime()->format('H:i:s.v')
                : null,
            'tyres' => $this->getTyres(),
        ];
    }
}
