<?php

namespace App\Entity;

use App\Repository\MProductpriceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MProductpriceRepository::class)
 * @ORM\Table(name="m_productprice")
 */
class MProductprice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $m_productprice_id;

    /**
     * @ORM\ManyToOne(targetEntity=MPricelistVersion::class, inversedBy="m_productprices")
     * @ORM\JoinColumn(referencedColumnName="m_pricelist_version_id", nullable=false)
     */
    private $m_pricelist_version;

    /**
     * @ORM\ManyToOne(targetEntity=MProduct::class, inversedBy="m_productprices")
     * @ORM\JoinColumn(referencedColumnName="m_product_id", nullable=false)
     */
    private $m_product;

    /**
     * @ORM\Column(type="integer")
     */
    private $m_product_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="float")
     */
    private $pricelist;

    /**
     * @ORM\Column(type="float")
     */
    private $pricestd;

    /**
     * @ORM\Column(type="float")
     */
    private $pricelimit;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_org_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $sm_marca_id;

    public function getId(): ?int
    {
        return $this->getMProductpriceId();
    }

    public function getMProductpriceId(): ?int
    {
        return $this->m_productprice_id;
    }

    public function setMProductpriceId(int $m_productprice_id): self
    {
        $this->m_productprice_id = $m_productprice_id;

        return $this;
    }

    public function getMPricelistVersion(): ?MPricelistVersion
    {
        return $this->m_pricelist_version;
    }

    public function setMPricelistVersion(?MPricelistVersion $m_pricelist_version): self
    {
        $this->m_pricelist_version = $m_pricelist_version;

        return $this;
    }

    public function getMProduct(): ?MProduct
    {
        return $this->m_product;
    }

    public function setMProduct(?MProduct $m_product): self
    {
        $this->m_product = $m_product;

        return $this;
    }

    public function getMProductId(): ?int
    {
        return $this->m_product_id;
    }

    public function setMProductId(int $m_product_id): self
    {
        $this->m_product_id = $m_product_id;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

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

    public function getPricelist(): ?float
    {
        return $this->pricelist;
    }

    public function setPricelist(float $pricelist): self
    {
        $this->pricelist = $pricelist;

        return $this;
    }

    public function getPricestd(): ?float
    {
        return $this->pricestd;
    }

    public function setPricestd(float $pricestd): self
    {
        $this->pricestd = $pricestd;

        return $this;
    }

    public function getPricelimit(): ?float
    {
        return $this->pricelimit;
    }

    public function setPricelimit(float $pricelimit): self
    {
        $this->pricelimit = $pricelimit;

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

    public function getSmMarcaId(): ?int
    {
        return $this->sm_marca_id;
    }

    public function setSmMarcaId(int $sm_marca_id): self
    {
        $this->sm_marca_id = $sm_marca_id;

        return $this;
    }
}
