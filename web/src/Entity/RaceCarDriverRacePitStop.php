<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RaceCarDriverRacePitStopRepository")
 * @ORM\Table(name="race_car_driver_race_pit_stops")
 * @UniqueEntity(
 *   fields={"raceCarDriver", "lap"},
 *   message="This Race Car Driver Pit Stop was already added"
 * )
 */
class RaceCarDriverRacePitStop implements Interfaces\ArrayInterface, TimestampableInterface
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
     * @ORM\Column(type="time_with_milliseconds")
     * @Assert\NotBlank()
     */
    private $time;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $timeOfDay;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RaceCarDriver", inversedBy="raceCarDriverRacePitStops")
     * @ORM\JoinColumn()
     */
    private $raceCarDriver;

    public function __toString()
    {
        $race = $this->getRaceCarDriver()->getRace();
        $driver = $this->getRaceCarDriver()->getDriver();
        $lap = $this->getLap();

        return $driver . ' @ ' . $race . ' (pit stop at lap ' . $lap . ')';
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
            'lap' => $this->getLap(),
        ];
    }
}