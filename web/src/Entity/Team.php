<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 * @ORM\Table(name="teams")
 */
class Team implements Interfaces\ArrayInterface, TimestampableInterface
{
    use TimestampableTrait;
    use Traits\SlugTrait;

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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\Country()
     * @Assert\NotBlank()
     */
    private $countryCode;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $debutedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $defunctedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TeamVehicle", mappedBy="team")
     */
    private $teamVehicles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SeasonTeam", mappedBy="team")
     */
    private $seasonTeams;

    public function __construct()
    {
        $this->teamVehicles = new ArrayCollection();
        $this->seasonTeams = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getDebutedAt(): ?\DateTimeInterface
    {
        return $this->debutedAt;
    }

    public function setDebutedAt(?\DateTimeInterface $debutedAt): self
    {
        $this->debutedAt = $debutedAt;

        return $this;
    }

    public function getDefunctedAt(): ?\DateTimeInterface
    {
        return $this->defunctedAt;
    }

    public function setDefunctedAt(?\DateTimeInterface $defunctedAt): self
    {
        $this->defunctedAt = $defunctedAt;

        return $this;
    }

    /**
     * @return Collection|TeamVehicle[]
     */
    public function getTeamVehicles(): Collection
    {
        return $this->teamVehicles;
    }

    public function addTeamVehicle(TeamVehicle $teamVehicle): self
    {
        if (!$this->teamVehicles->contains($teamVehicle)) {
            $this->teamVehicles[] = $teamVehicle;
            $teamVehicle->setTeam($this);
        }

        return $this;
    }

    public function removeTeamVehicle(TeamVehicle $teamVehicle): self
    {
        if ($this->teamVehicles->contains($teamVehicle)) {
            $this->teamVehicles->removeElement($teamVehicle);
            if ($teamVehicle->getTeam() === $this) {
                $teamVehicle->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SeasonTeam[]
     */
    public function getSeasonTeams(): Collection
    {
        return $this->seasonTeams;
    }

    public function addSeasonTeam(SeasonTeam $seasonTeam): self
    {
        if (!$this->seasonTeams->contains($seasonTeam)) {
            $this->seasonTeams[] = $seasonTeam;
            $seasonTeam->setTeam($this);
        }

        return $this;
    }

    public function removeSeasonTeam(SeasonTeam $seasonTeam): self
    {
        if ($this->seasonTeams->contains($seasonTeam)) {
            $this->seasonTeams->removeElement($seasonTeam);
            if ($seasonTeam->getTeam() === $this) {
                $seasonTeam->setTeam(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'location' => $this->getLocation(),
            'country_code' => $this->getCountryCode(),
            'url' => $this->getUrl(),
            'debuted_at' => $this->getDebutedAt()->format('Y-m-d'),
            'defuncted_at' => $this->getDefunctedAt()
                ? $this->getDefunctedAt()->format('Y-m-d')
                : null,
        ];
    }
}
