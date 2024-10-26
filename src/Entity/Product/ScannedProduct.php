<?php

namespace App\Entity\Product;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity]
class ScannedProduct extends Product
{
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['create'])]
    #[Groups(['edit_product'])]
    private string $barcode;

    #[ORM\Column(length: 1, nullable: true)]
    #[Groups(['show_product', 'edit_product'])]
    private ?string $nutriscore = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['show_product', 'edit_product'])]
    private ?int $novagroup = null;

    #[ORM\Column(length: 1, nullable: true)]
    #[Groups(['show_product', 'edit_product'])]
    private ?int $ecoscore = null;

    public function getBarcode(): string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): static
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getNutriscore(): ?string
    {
        return $this->nutriscore;
    }

    public function setNutriscore(?string $nutriscore): static
    {
        $this->nutriscore = $nutriscore;

        return $this;
    }

    public function getNovagroup(): ?int
    {
        return $this->novagroup;
    }

    public function setNovagroup(?int $novagroup): static
    {
        $this->novagroup = $novagroup;

        return $this;
    }

    public function getEcoscore(): ?int
    {
        return $this->ecoscore;
    }

    public function setEcoscore(?int $ecoscore): static
    {
        $this->ecoscore = $ecoscore;

        return $this;
    }
}
