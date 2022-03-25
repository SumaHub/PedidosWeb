<?php

namespace App\Entity;

use App\Repository\CCurrencyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CCurrencyRepository::class)
 * @ORM\Table(name="c_currency")
 */
class CCurrency
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $c_currency_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_org_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $iso_code;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $cursymbol;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $stdprecision;

    /**
     * @ORM\Column(type="integer")
     */
    private $roundofffactor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCCurrencyId(): ?int
    {
        return $this->c_currency_id;
    }

    public function setCCurrencyId(int $c_currency_id): self
    {
        $this->c_currency_id = $c_currency_id;

        return $this;
    }

    public function getAdOrgId(): ?int
    {
        return $this->ad_org_id;
    }

    public function setAdOrgId(int $ad_org_id): self
    {
        $this->ad_org_id = $ad_org_id;

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

    public function getIsoCode(): ?string
    {
        return $this->iso_code;
    }

    public function setIsoCode(string $iso_code): self
    {
        $this->iso_code = $iso_code;

        return $this;
    }

    public function getCursymbol(): ?string
    {
        return $this->cursymbol;
    }

    public function setCursymbol(string $cursymbol): self
    {
        $this->cursymbol = $cursymbol;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStdprecision(): ?int
    {
        return $this->stdprecision;
    }

    public function setStdprecision(int $stdprecision): self
    {
        $this->stdprecision = $stdprecision;

        return $this;
    }

    public function getRoundofffactor(): ?int
    {
        return $this->roundofffactor;
    }

    public function setRoundofffactor(int $roundofffactor): self
    {
        $this->roundofffactor = $roundofffactor;

        return $this;
    }
}
