<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DriverRepository")
 * @ORM\Table(name="drivers")
 */
class Driver implements Interfaces\ArrayInterface, TimestampableInterface
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Car", mappedBy="driver")
     */
    private $cars;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RaceCarDriver", mappedBy="driver")
     */
    private $raceCarDrivers;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
        $this->raceCarDrivers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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
            $car->setDriver($this);
        }

        return $this;
    }

    public function removeCar(Car $car): self
    {
        if ($this->cars->contains($car)) {
            $this->cars->removeElement($car);
            if ($car->getDriver() === $this) {
                $car->setDriver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Car[]
     */
    public function getRaceCarDrivers(): Collection
    {
        return $this->raceCarDrivers;
    }

    public function addRaceCarDriver(RaceCarDriver $raceCarDriver): self
    {
        if (!$this->raceCarDrivers->contains($raceCarDriver)) {
            $this->raceCarDrivers[] = $raceCarDriver;
            $raceCarDriver->setDriver($this);
        }

        return $this;
    }

    public function removeRaceCarDriver(RaceCarDriver $raceCarDriver): self
    {
        if ($this->raceCarDrivers->contains($raceCarDriver)) {
            $this->cars->removeElement($raceCarDriver);
            if ($raceCarDriver->getDriver() === $this) {
                $raceCarDriver->setDriver(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'country_code' => $this->getCountryCode(),
            'url' => $this->getUrl(),
        ];
    }
}
