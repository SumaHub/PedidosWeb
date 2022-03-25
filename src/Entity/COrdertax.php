<?php

namespace App\Entity;

use App\Repository\COrdertaxRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=COrdertaxRepository::class)
 */
class COrdertax
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $c_ordertax_uu;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_order_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_tax_id;

    /**
     * @ORM\Column(type="float")
     */
    private $taxamt;

    /**
     * @ORM\Column(type="float")
     */
    private $taxbaseamt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCOrdertaxUu(): ?string
    {
        return $this->c_ordertax_uu;
    }

    public function setCOrdertaxUu(string $c_ordertax_uu): self
    {
        $this->c_ordertax_uu = $c_ordertax_uu;

        return $this;
    }

    public function getCOrderId(): ?int
    {
        return $this->c_order_id;
    }

    public function setCOrderId(int $c_order_id): self
    {
        $this->c_order_id = $c_order_id;

        return $this;
    }

    public function getCTaxId(): ?int
    {
        return $this->c_tax_id;
    }

    public function setCTaxId(int $c_tax_id): self
    {
        $this->c_tax_id = $c_tax_id;

        return $this;
    }

    public function getTaxamt(): ?float
    {
        return $this->taxamt;
    }

    public function setTaxamt(float $taxamt): self
    {
        $this->taxamt = $taxamt;

        return $this;
    }

    public function getTaxbaseamt(): ?float
    {
        return $this->taxbaseamt;
    }

    public function setTaxbaseamt(float $taxbaseamt): self
    {
        $this->taxbaseamt = $taxbaseamt;

        return $this;
    }
}
