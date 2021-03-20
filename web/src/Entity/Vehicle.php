<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VehicleRepository")
 * @ORM\Table(name="vehicles")
 * @Vich\Uploadable()
 */
class Vehicle implements Interfaces\ArrayInterface, TimestampableInterface
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
    private $type;

    /**
     * @Vich\UploadableField(
     *   mapping="vehicle",
     *   fileNameProperty="fileEmbedded.name",
     *   size="fileEmbedded.size",
     *   mimeType="fileEmbedded.mimeType",
     *   originalName="fileEmbedded.originalName",
     *   dimensions="fileEmbedded.dimensions"
     * )
     * @Assert\NotBlank()
     * @Assert\File(
     *   maxSize="5M",
     *   mimeTypes={"model/gltf-binary", "application/octet-stream"},
     *   mimeTypesMessage="Please upload a valid .glb file!"
     * )
     */
    private $file;

    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     *
     * @var EmbeddedFile
     */
    private $fileEmbedded;

    public function __construct()
    {
        $this->fileEmbedded = new EmbeddedFile();
    }

    public function __toString()
    {
        return $this->getName() . ' (' . $this->getType() . ')';
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getFileEmbedded(): ?EmbeddedFile
    {
        return $this->fileEmbedded;
    }

    public function setFileEmbedded(?EmbeddedFile $fileEmbedded): self
    {
        $this->fileEmbedded = $fileEmbedded;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'type' => $this->getType(),
        ];
    }
}
