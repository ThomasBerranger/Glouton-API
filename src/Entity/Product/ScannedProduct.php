<?php

namespace App\Entity\Product;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Serializer\Attribute\Groups;

#[Entity]
class ScannedProduct extends Product
{
    #[ORM\Column(length: 255)]
    private string $barcode;

    #[ORM\Column(length: 1, nullable: true)]
    #[Groups(['products.show'])]
    private ?string $nutriscore = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['products.show'])]
    private ?int $novagroup = null;

    #[ORM\Column(length: 1, nullable: true)]
    #[Groups(['products.show'])]
    private ?string $ecoscore = null;

    public function getBarcode(): string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): void
    {
        $this->barcode = $barcode;
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

    public function getEcoscore(): ?string
    {
        return $this->ecoscore;
    }

    public function setEcoscore(?string $ecoscore): static
    {
        $this->ecoscore = $ecoscore;

        return $this;
    }
}
