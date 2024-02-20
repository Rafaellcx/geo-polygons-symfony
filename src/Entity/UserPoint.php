<?php

namespace App\Entity;

use App\Repository\UserPointRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserPointRepository::class)]
class UserPoint
{
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7)]
    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    #[Assert\Range(min: -90.0, max: 90.0,)]
    #[Assert\Regex(pattern: '/^-?\d{1,3}\.\d{1,9}$/')]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7)]
    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    #[Assert\Range(min: -180.0, max: 180.0,)]
    #[Assert\Regex(pattern: '/^-?\d{1,3}\.\d{1,9}$/')]
    private ?string $longitude = null;

    #[ORM\Column(type: 'geometry')]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private $geom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $updated_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $deleted_at = null;

    #[MaxDepth(1)]
    #[ORM\ManyToOne(targetEntity: MunicipalGeometry::class, inversedBy: 'userPoints')]
    private ?MunicipalGeometry $municipal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getGeom()
    {
        return $this->geom;
    }

    public function setGeom($geom): static
    {
        $this->geom = $geom;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param DateTimeInterface|null $created_at
     */
    public function setCreatedAt(?DateTimeInterface $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }

    /**
     * @param DateTimeInterface|null $updated_at
     */
    public function setUpdatedAt(?DateTimeInterface $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deleted_at;
    }

    /**
     * @param DateTimeInterface|null $deleted_at
     */
    public function setDeletedAt(?DateTimeInterface $deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }

    public function getMunicipal(): ?MunicipalGeometry
    {
        return $this->municipal;
    }

    public function setMunicipal(?MunicipalGeometry $municipal): static
    {
        $this->municipal = $municipal;

        return $this;
    }
}
