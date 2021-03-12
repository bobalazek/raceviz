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
     * @ORM\OneToMany(targetEntity="App\Entity\RaceDriver", mappedBy="team")
     */
    private $raceDrivers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SeasonTeam", mappedBy="team")
     */
    private $seasonTeams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SeasonDriver", mappedBy="team")
     */
    private $seasonDrivers;

    public function __construct()
    {
        $this->raceDrivers = new ArrayCollection();
        $this->seasonTeams = new ArrayCollection();
        $this->seasonDrivers = new ArrayCollection();
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
     * @return Collection|RaceDriver[]
     */
    public function getRaceDrivers(): Collection
    {
        return $this->raceDrivers;
    }

    public function addRaceDriver(RaceDriver $raceDriver): self
    {
        if (!$this->raceDrivers->contains($raceDriver)) {
            $this->raceDrivers[] = $raceDriver;
            $raceDriver->setTeam($this);
        }

        return $this;
    }

    public function removeRaceDriver(RaceDriver $raceDriver): self
    {
        if ($this->raceDrivers->contains($raceDriver)) {
            $this->raceDrivers->removeElement($raceDriver);
            if ($raceDriver->getTeam() === $this) {
                $raceDriver->setTeam(null);
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

    /**
     * @return Collection|SeasonDriver[]
     */
    public function getSeasonDrivers(): Collection
    {
        return $this->seasonDrivers;
    }

    public function addSeasonDriver(SeasonDriver $seasonDriver): self
    {
        if (!$this->seasonDrivers->contains($seasonDriver)) {
            $this->seasonDrivers[] = $seasonDriver;
            $seasonDriver->setTeam($this);
        }

        return $this;
    }

    public function removeSeasonDriver(SeasonDriver $seasonDriver): self
    {
        if ($this->seasonDrivers->contains($seasonDriver)) {
            $this->seasonDrivers->removeElement($seasonDriver);
            if ($seasonDriver->getTeam() === $this) {
                $seasonDriver->setTeam(null);
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
