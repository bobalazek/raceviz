<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RaceIncidentRaceDriverRepository")
 * @ORM\Table(name="race_incident_race_drivers")
 * @UniqueEntity(
 *   fields={"raceIncident", "raceDriver"},
 *   message="This Race Driver was already added to this Race Incident"
 * )
 */
class RaceIncidentRaceDriver implements Interfaces\ArrayInterface, TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RaceIncident", inversedBy="raceIncidentRaceDrivers")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $raceIncident;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RaceDriver", inversedBy="raceIncidentRaceDrivers")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $raceDriver;

    public function __toString()
    {
        $raceIncident = $this->getRaceIncident();
        $raceDriver = $this->getRaceDriver();

        return $raceIncident . ' @ ' . $raceDriver;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRaceIncident(): ?RaceIncident
    {
        return $this->raceIncident;
    }

    public function setRaceIncident(?RaceIncident $raceIncident): self
    {
        $this->raceIncident = $raceIncident;

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
            'description' => $this->getDescription(),
            'race_driver' => $this->getRaceDriver()
                ? $this->getRaceDriver()->toArray()
                : null,
        ];
    }
}
