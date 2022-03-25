<?php

namespace App\Entity;

use App\Repository\MPricelistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MPricelistRepository::class)
 * @ORM\Table(name="m_pricelist")
 */
class MPricelist
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $m_pricelist_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $m_pricelist_uu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $issopricelist;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isdefault;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_currency_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceprecision;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_org_id;

    /**
     * @ORM\OneToMany(targetEntity=MPricelistVersion::class, mappedBy="m_pricelist")
     */
    private $m_pricelist_versions;

    public function __construct()
    {
        $this->m_pricelist_versions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->getMPricelistId();
    }

    public function getMPricelistId(): ?int
    {
        return $this->m_pricelist_id;
    }

    public function setMPricelistId(int $m_pricelist_id): self
    {
        $this->m_pricelist_id = $m_pricelist_id;

        return $this;
    }

    public function getMPricelistUu(): ?string
    {
        return $this->m_pricelist_uu;
    }

    public function setMPricelistUu(string $m_pricelist_uu): self
    {
        $this->m_pricelist_uu = $m_pricelist_uu;

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

    public function getIssopricelist(): ?string
    {
        return $this->issopricelist;
    }

    public function setIssopricelist(string $issopricelist): self
    {
        $this->issopricelist = $issopricelist;

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

    public function getCCurrencyId(): ?int
    {
        return $this->c_currency_id;
    }

    public function setCCurrencyId(int $c_currency_id): self
    {
        $this->c_currency_id = $c_currency_id;

        return $this;
    }

    public function getPriceprecision(): ?int
    {
        return $this->priceprecision;
    }

    public function setPriceprecision(int $priceprecision): self
    {
        $this->priceprecision = $priceprecision;

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

    /**
     * @return Collection|MPricelistVersion[]
     */
    public function getMPricelistVersions(): Collection
    {
        return $this->m_pricelist_versions;
    }

    public function addMPricelistVersion(MPricelistVersion $mPricelistVersion): self
    {
        if (!$this->m_pricelist_version->contains($mPricelistVersion)) {
            $this->m_pricelist_version[] = $mPricelistVersion;
            $mPricelistVersion->setMPricelist($this);
        }

        return $this;
    }

    public function removeMPricelistVersion(MPricelistVersion $mPricelistVersion): self
    {
        if ($this->m_pricelist_version->removeElement($mPricelistVersion)) {
            // set the owning side to null (unless already changed)
            if ($mPricelistVersion->getMPricelist() === $this) {
                $mPricelistVersion->setMPricelist(null);
            }
        }

        return $this;
    }
}
