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
    private $timeDuration;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $timeOfDay;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $tyres;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RaceDriver", inversedBy="raceDriverRaceLaps")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
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

    public function setLap(?int $lap): self
    {
        $this->lap = $lap;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getTimeDuration(): ?\DateTimeInterface
    {
        return $this->timeDuration;
    }

    public function setTimeDuration(?\DateTimeInterface $timeDuration): self
    {
        $this->timeDuration = $timeDuration;

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

    public function getTyres(): ?string
    {
        return $this->tyres;
    }

    public function setTyres(?string $tyres): self
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
            'lap' => $this->getLap(),
            'position' => $this->getPosition(),
            'time_duration' => $this->getTimeDuration()
                ? $this->getTimeDuration()->format('H:i:s.v')
                : null,
            'time_of_day' => $this->getTimeOfDay()
                ? $this->getTimeOfDay()->format('H:i:s')
                : null,
            'tyres' => $this->getTyres(),
        ];
    }
}
