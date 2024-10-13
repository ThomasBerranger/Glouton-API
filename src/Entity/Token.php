<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
class Token
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $expiry_date = null;

    #[ORM\OneToOne(mappedBy: 'token')]
    private ?User $owner = null;

    public function __construct()
    {
        $this->expiry_date = new \DateTime('now + 1 month');
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getExpiryDate(): ?\DateTimeInterface
    {
        return $this->expiry_date;
    }

    public function setExpiryDate(\DateTimeInterface $expiry_date): static
    {
        $this->expiry_date = $expiry_date;

        return $this;
    }

    public function isValid(): bool
    {
        return new \DateTime() <= $this->expiry_date;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        // unset the owning side of the relation if necessary
        if (null === $owner && null !== $this->owner) {
            $this->owner->setToken(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $owner && $owner->getToken() !== $this) {
            $owner->setToken($this);
        }

        $this->owner = $owner;

        return $this;
    }
}
