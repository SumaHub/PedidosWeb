<?php

namespace App\Entity;

use App\Repository\CTaxRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CTaxRepository::class)
 * @ORM\Table(name="C_Tax")
 */
class CTax
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $c_tax_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_client_id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_taxcategory_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isdefault;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isdocumentlevel;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $issalestax;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $sopotype;

    /**
     * @ORM\Column(type="datetime")
     */
    private $validfrom;

    /**
     * @ORM\Column(type="float")
     */
    private $rate;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_country_id;

    public function getId(): ?int
    {
        return $this->getCTaxId();
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

    public function getAdClientId(): ?int
    {
        return $this->ad_client_id;
    }

    public function setAdClientId(int $ad_client_id): self
    {
        $this->ad_client_id = $ad_client_id;

        return $this;
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

    public function getCTaxcategoryId(): ?int
    {
        return $this->c_taxcategory_id;
    }

    public function setCTaxcategoryId(int $c_taxcategory_id): self
    {
        $this->c_taxcategory_id = $c_taxcategory_id;

        return $this;
    }

    public function getIsactive(): ?string
    {
        return $this->isactive;
    }

    public function setIsactive(string $isactive): self
    {
        $this->isactive = $isactive;

        return $this;
    }

    public function getIsdefault(): ?string
    {
        return $this->isdefault;
    }

    public function setIsdefault(string $isdefault): self
    {
        $this->isdefault = $isdefault;

        return $this;
    }

    public function getIsdocumentlevel(): ?string
    {
        return $this->isdocumentlevel;
    }

    public function setIsdocumentlevel(string $isdocumentlevel): self
    {
        $this->isdocumentlevel = $isdocumentlevel;

        return $this;
    }

    public function getIssalestax(): ?string
    {
        return $this->issalestax;
    }

    public function setIssalestax(string $issalestax): self
    {
        $this->issalestax = $issalestax;

        return $this;
    }

    public function getSopotype(): ?string
    {
        return $this->sopotype;
    }

    public function setSopotype(string $sopotype): self
    {
        $this->sopotype = $sopotype;

        return $this;
    }

    public function getValidfrom(): ?\DateTimeInterface
    {
        return $this->validfrom;
    }

    public function setValidfrom(\DateTimeInterface $validfrom): self
    {
        $this->validfrom = $validfrom;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getCCountryId(): ?int
    {
        return $this->c_country_id;
    }

    public function setCCountryId(int $c_country_id): self
    {
        $this->c_country_id = $c_country_id;

        return $this;
    }
}
