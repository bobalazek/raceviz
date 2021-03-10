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
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $series;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\Country()
     */
    private $countryCode;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="datetime")
     */
    private $debutedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $defunctedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Car", mappedBy="team")
     */
    private $cars;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
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

    public function getSeries(): ?string
    {
        return $this->series;
    }

    public function setSeries(string $series): self
    {
        $this->series = $series;

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
     * @return Collection|Car[]
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): self
    {
        if (!$this->cars->contains($car)) {
            $this->cars[] = $car;
            $car->setTeam($this);
        }

        return $this;
    }

    public function removeCar(Car $car): self
    {
        if ($this->cars->contains($car)) {
            $this->cars->removeElement($car);
            if ($car->getTeam() === $this) {
                $car->setTeam(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'series' => $this->getSeries(),
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
