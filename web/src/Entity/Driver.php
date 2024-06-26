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
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $lastName;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ergastDriverId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SeasonDriver", mappedBy="driver")
     */
    private $seasonDrivers;

    public function __construct()
    {
        $this->seasonDrivers = new ArrayCollection();
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

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
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

    public function getErgastDriverId(): ?string
    {
        return $this->ergastDriverId;
    }

    public function setErgastDriverId(?string $ergastDriverId): self
    {
        $this->ergastDriverId = $ergastDriverId;

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
            $seasonDriver->setDriver($this);
        }

        return $this;
    }

    public function removeSeasonDriver(SeasonDriver $seasonDriver): self
    {
        if ($this->seasonDrivers->contains($seasonDriver)) {
            $this->seasonDrivers->removeElement($seasonDriver);
            if ($seasonDriver->getDriver() === $this) {
                $seasonDriver->setDriver(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        $firstName = $this->getFirstName();
        $lastName = $this->getLastName();

        return [
            'id' => $this->getId(),
            'name' => $firstName . ' ' . $lastName,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'slug' => $this->getSlug(),
            'country_code' => $this->getCountryCode(),
            'url' => $this->getUrl(),
        ];
    }
}
