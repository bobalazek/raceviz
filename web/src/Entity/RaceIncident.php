<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RaceIncidentRepository")
 * @ORM\Table(name="race_incidents")
 */
class RaceIncident implements Interfaces\ArrayInterface, TimestampableInterface
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
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $flag;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     */
    private $lap;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $lapSector;

    /**
     * Where on the lap did the accident happen 0 (lap start) - 1 (360 degrees around the track)?
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $lapLocation;

    /**
     * @ORM\Column(type="time_with_milliseconds")
     */
    private $timeDuration;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $timeOfDay;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Race", inversedBy="raceIncidents")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $race;

    public function __toString()
    {
        return $this->getDescription() . ' @ ' . $this->getRace();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function setFlag(?string $flag): self
    {
        $this->flag = $flag;

        return $this;
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

    public function getLapSector(): ?int
    {
        return $this->lapSector;
    }

    public function setLapSector(?int $lapSector): self
    {
        $this->lapSector = $lapSector;

        return $this;
    }

    public function getLapLocation(): ?int
    {
        return $this->lapLocation;
    }

    public function setLapLocation(?int $lapLocation): self
    {
        $this->lapLocation = $lapLocation;

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

    public function getRace(): ?Race
    {
        return $this->race;
    }

    public function setRace(?Race $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'description' => $this->getDescription(),
            'flag' => $this->getFlag(),
            'lap' => $this->getLap(),
            'lap_sector' => $this->getLapSector(),
            'lap_location' => $this->getLapLocation(),
            'time_duration' => $this->getTimeDuration()
                ? $this->getTimeDuration()->format('H:i:s.v')
                : null,
            'time_of_day' => $this->getTimeOfDay()
                ? $this->getTimeOfDay()->format('H:i:s')
                : null,
        ];
    }
}
