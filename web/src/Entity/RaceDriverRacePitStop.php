<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RaceDriverRacePitStopRepository")
 * @ORM\Table(name="race_driver_race_pit_stops")
 * @UniqueEntity(
 *   fields={"raceDriver", "lap"},
 *   message="This Race Driver Pit Stop was already added"
 * )
 */
class RaceDriverRacePitStop implements Interfaces\ArrayInterface, TimestampableInterface
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
     * @Assert\LessThan(32768)
     */
    private $lap;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\RaceDriver", inversedBy="raceDriverRacePitStops")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $raceDriver;

    public function __toString()
    {
        $race = $this->getRaceDriver()->getRace();
        $seasonDriver = $this->getRaceDriver()->getSeasonDriver();
        $lap = $this->getLap();

        return $seasonDriver . ' @ ' . $race . ' (pit stop at lap ' . $lap . ')';
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
            'time_duration' => $this->getTimeDuration()
                ? $this->getTimeDuration()->format('H:i:s.v')
                : null,
            'time_of_day' => $this->getTimeOfDay()
                ? $this->getTimeOfDay()->format('H:i:s')
                : null,
        ];
    }
}
