<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeasonTeamRepository")
 * @ORM\Table(name="season_teams")
 * @UniqueEntity(
 *   fields={"season", "team"},
 *   message="This Season Team was already added"
 * )
 * @Vich\Uploadable()
 */
class SeasonTeam implements Interfaces\ArrayInterface, TimestampableInterface
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Season", inversedBy="seasonTeams")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $season;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="seasonTeams")
     * @ORM\JoinColumn()
     * @Assert\NotBlank()
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicle")
     * @Assert\NotBlank()
     */
    private $vehicle;

    public function __toString()
    {
        return $this->getSeason() . ' @ ' . $this->getTeam();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

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

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'team' => $this->getTeam()
                ? $this->getTeam()->toArray()
                : null,
        ];
    }
}
