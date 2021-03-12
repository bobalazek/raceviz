<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RaceDriverRaceLapRepository")
 * @ORM\Table(name="race_driver_race_laps")
 * @UniqueEntity(
 *   fields={"raceDriver", "lap"},
 *   message="This Race Driver Lap was already added"
 * )
 */
class RaceDriverRaceLap implements Interfaces\ArrayInterface, TimestampableInterface
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
    private $lap;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     */
    private $position;

    /**
     * @ORM\Column(type="time_with_milliseconds")
     * @Assert\NotBlank()
     */
    private $time;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $timeOfDay;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RaceDriver", inversedBy="raceDriverRaceLaps")
     * @ORM\JoinColumn()
     */
    private $raceDriver;

    public function __toString()
    {
        $race = $this->getRaceDriver()->getRace();
        $driver = $this->getRaceDriver()->getDriver();
        $lap = $this->getLap();

        return $driver . ' @ ' . $race . ' (lap ' . $lap . ')';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLap(): ?int
    {
        return $this->lap;
    }

    public function setLap(string $lap): self
    {
        $this->lap = $lap;

        return $this;
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

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getTimeOfDay(): ?\DateTimeInterface
    {
        return $this->timeOfDay;
    }

    public function setTimeOfDay(?\DateTimeInterface $timeOfDay): self
    {
        $this->timeOfDay = $timeOfDay;

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
            'lap' => $this->getLap(),
            'position' => $this->getPosition(),
        ];
    }
}
