<?php

namespace App\Entity;

use App\Repository\MunicipalGeometryRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: MunicipalGeometryRepository::class)]
class MunicipalGeometry
{
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 2)]
    private ?string $uf = null;

    #[ORM\Column(type: 'geometry')]
    private $geom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $updated_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $deleted_at = null;

    #[MaxDepth(1)]
    #[ORM\OneToMany(targetEntity: UserPoint::class, mappedBy: 'municipal', orphanRemoval: true)]
    private Collection $userPoints;

    public function __construct()
    {
        $this->userPoints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUf(): ?string
    {
        return $this->uf;
    }

    public function setUf(string $uf): static
    {
        $this->uf = $uf;

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

    /**
     * @return Collection<int, UserPoint>
     */
    public function getUserPoints(): Collection
    {
        return $this->userPoints;
    }

    public function addUserPoint(UserPoint $userPoint): static
    {
        if (!$this->userPoints->contains($userPoint)) {
            $this->userPoints->add($userPoint);
            $userPoint->setMunicipal($this);
        }

        return $this;
    }

    public function removeUserPoint(UserPoint $userPoint): static
    {
        if ($this->userPoints->removeElement($userPoint)) {
            // set the owning side to null (unless already changed)
            if ($userPoint->getMunicipal() === $this) {
                $userPoint->setMunicipal(null);
            }
        }

        return $this;
    }
}
