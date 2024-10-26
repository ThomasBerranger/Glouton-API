<?php

namespace App\Entity\Product;

use App\Entity\ExpirationDate;
use App\Entity\User;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'scanned' => 'ScannedProduct',
    'custom' => 'CustomProduct',
])]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_product', 'edit_product', 'show_recipe'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['show_product', 'edit_product'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['show_product', 'edit_product'])]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['show_product', 'edit_product'])]
    private ?\DateTimeInterface $finishedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['show_product', 'edit_product'])]
    private ?\DateTimeInterface $addedToListAt = null;

    /**
     * @var Collection<int, ExpirationDate>
     */
    #[ORM\OneToMany(targetEntity: ExpirationDate::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['show_product', 'edit_product'])]
    private Collection $expirationDates;

    public function __construct()
    {
        $this->expirationDates = new ArrayCollection();
    }

    public function getId(): ?Uuid
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeInterface
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeInterface $finishedAt): static
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    public function getAddedToListAt(): ?\DateTimeInterface
    {
        return $this->addedToListAt;
    }

    public function setAddedToListAt(?\DateTimeInterface $addedToListAt): static
    {
        $this->addedToListAt = $addedToListAt;

        return $this;
    }

    /**
     * @return Collection<int, ExpirationDate>
     */
    public function getExpirationDates(): Collection
    {
        return $this->expirationDates;
    }

    public function addExpirationDate(ExpirationDate $expirationDate): static
    {
        if (!$this->expirationDates->contains($expirationDate)) {
            $this->expirationDates->add($expirationDate);
            $expirationDate->setProduct($this);
        }

        return $this;
    }

    public function removeExpirationDate(ExpirationDate $expirationDate): static
    {
        if ($this->expirationDates->removeElement($expirationDate)) {
            // set the owning side to null (unless already changed)
            if ($expirationDate->getProduct() === $this) {
                $expirationDate->setProduct(null);
            }
        }

        return $this;
    }
}
