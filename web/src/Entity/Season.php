<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeasonRepository")
 * @ORM\Table(name="seasons")
 */
class Season implements Interfaces\ArrayInterface, TimestampableInterface
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
     * @ORM\Column(type="string", length=16)
     * @Assert\NotBlank()
     */
    private $series;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Race", mappedBy="season")
     */
    private $races;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SeasonTeam", mappedBy="season")
     */
    private $seasonTeams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SeasonDriver", mappedBy="season")
     */
    private $seasonDrivers;

    public function __construct()
    {
        $this->races = new ArrayCollection();
        $this->seasonTeams = new ArrayCollection();
        $this->seasonDrivers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName() . ' @ ' . $this->getSeries();
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

    public function getSeries(): ?string
    {
        return $this->series;
    }

    public function setSeries(string $series): self
    {
        $this->series = $series;

        return $this;
    }

    /**
     * @return Collection|Race[]
     */
    public function getRaces(): Collection
    {
        return $this->races;
    }

    public function addRace(Race $race): self
    {
        if (!$this->races->contains($race)) {
            $this->races[] = $race;
            $race->setSeason($this);
        }

        return $this;
    }

    public function removeRace(Race $race): self
    {
        if ($this->races->contains($race)) {
            $this->races->removeElement($race);
            if ($race->getSeason() === $this) {
                $race->setSeason(null);
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
            $seasonTeam->setSeason($this);
        }

        return $this;
    }

    public function removeSeasonTeam(SeasonTeam $seasonTeam): self
    {
        if ($this->seasonTeams->contains($seasonTeam)) {
            $this->seasonTeams->removeElement($seasonTeam);
            if ($seasonTeam->getSeason() === $this) {
                $seasonTeam->setSeason(null);
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
            $seasonDriver->setSeason($this);
        }

        return $this;
    }

    public function removeSeasonDriver(SeasonDriver $seasonDriver): self
    {
        if ($this->seasonDrivers->contains($seasonDriver)) {
            $this->seasonDrivers->removeElement($seasonDriver);
            if ($seasonDriver->getSeason() === $this) {
                $seasonDriver->setSeason(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
        ];
    }
}
