<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RaceDriverRaceResultRepository")
 * @ORM\Table(name="race_driver_race_results")
 * @UniqueEntity(
 *   fields={"raceDriver"},
 *   message="This Race Driver was already added"
 * )
 */
class RaceDriverRaceResult implements Interfaces\ArrayInterface, TimestampableInterface
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
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     */
    private $points;

    /**
     * @ORM\Column(type="time_with_milliseconds", nullable=true)
     */
    private $time;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $lapsBehind;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $statusNote;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\RaceDriver", inversedBy="raceDriverRaceResult")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $raceDriver;

    public function __toString()
    {
        $race = $this->getRaceDriver()->getRace();
        $driver = $this->getRaceDriver()->getDriver();
        $position = $this->getPosition();

        return $driver . ' @ ' . $race . ' (at result position ' . $position . ')';
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

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

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

    public function getLapsBehind(): ?int
    {
        return $this->lapsBehind;
    }

    public function setLapsBehind(int $lapsBehind): self
    {
        $this->lapsBehind = $lapsBehind;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusNote(): ?string
    {
        return $this->statusNote;
    }

    public function setStatusNote(string $statusNote): self
    {
        $this->statusNote = $statusNote;

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
            'points' => $this->getPoints(),
            'time' => $this->getTime()
                ? $this->getTime()->format('H:i:s.v')
                : null,
            'laps_behind' => $this->getLapsBehind(),
            'status' => $this->getStatus(),
            'status_note' => $this->getStatusNote(),
        ];
    }
}
