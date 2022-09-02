<?php

namespace App\Entity\Main;

use App\Repository\Main\COrderlineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=COrderlineRepository::class)
 * @ORM\Table(name="C_OrderLine")
 */
class COrderline
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $c_orderline_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $c_orderline_uu;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_client_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_org_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $line;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_order_id;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $qtyentered;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $qtyordered;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $qtydelivered;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $qtyreserved;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $qtyinvoiced;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $priceentered;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $pricelist;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $priceactual;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_tax_id;

    /**
     * @ORM\Column(type="decimal", precision=22, scale=2)
     */
    private $linenetamt;

    /**
     * @ORM\ManyToOne(targetEntity=MProduct::class)
     * @ORM\JoinColumn(referencedColumnName="m_product_id", nullable=false)
     */
    private $m_product;

    /**
     * @ORM\Column(type="integer")
     */
    private $m_product_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_activity_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_bpartner_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_bpartner_location_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $m_warehouse_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_uom_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="integer")
     */
    private $createdby;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(type="integer")
     */
    private $updatedby;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateordered;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_currency_id;

    public function getId(): ?int
    {
        return $this->getCOrderlineId();
    }

    public function getCOrderlineId(): ?int
    {
        return $this->c_orderline_id;
    }

    public function setCOrderlineId(int $c_orderline_id): self
    {
        $this->c_orderline_id = $c_orderline_id;

        return $this;
    }

    public function getCOrderlineUu(): ?string
    {
        return $this->c_orderline_uu;
    }

    public function setCOrderlineUu(string $c_orderline_uu): self
    {
        $this->c_orderline_uu = $c_orderline_uu;

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

    public function getAdOrgId(): ?int
    {
        return $this->ad_org_id;
    }

    public function setAdOrgId(int $ad_org_id): self
    {
        $this->ad_org_id = $ad_org_id;

        return $this;
    }

    public function getLine(): ?string
    {
        return $this->line;
    }

    public function setLine(string $line): self
    {
        $this->line = $line;

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

    public function getMProductId(): ?int
    {
        return $this->m_product_id;
    }

    public function setMProductId(int $m_product_id): self
    {
        $this->m_product_id = $m_product_id;

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

    public function getQtyentered(): ?string
    {
        return $this->qtyentered;
    }

    public function setQtyentered(string $qtyentered): self
    {
        $this->qtyentered = $qtyentered;

        return $this;
    }

    public function getQtyordered(): ?string
    {
        return $this->qtyordered;
    }

    public function setQtyordered(string $qtyordered): self
    {
        $this->qtyordered = $qtyordered;

        return $this;
    }

    public function getQtydelivered(): ?string
    {
        return $this->qtydelivered;
    }

    public function setQtydelivered(string $qtydelivered): self
    {
        $this->qtydelivered = $qtydelivered;

        return $this;
    }

    public function getQtyreserved(): ?string
    {
        return $this->qtyreserved;
    }

    public function setQtyreserved(string $qtyreserved): self
    {
        $this->qtyreserved = $qtyreserved;

        return $this;
    }

    public function getQtyinvoiced(): ?string
    {
        return $this->qtyinvoiced;
    }

    public function setQtyinvoiced(string $qtyinvoiced): self
    {
        $this->qtyinvoiced = $qtyinvoiced;

        return $this;
    }

    public function getPriceentered(): ?string
    {
        return $this->priceentered;
    }

    public function setPriceentered(string $priceentered): self
    {
        $this->priceentered = $priceentered;

        return $this;
    }

    public function getPricelist(): ?string
    {
        return $this->pricelist;
    }

    public function setPricelist(string $pricelist): self
    {
        $this->pricelist = $pricelist;

        return $this;
    }

    public function getPriceactual(): ?string
    {
        return $this->priceactual;
    }

    public function setPriceactual(string $priceactual): self
    {
        $this->priceactual = $priceactual;

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

    public function getLinenetamt(): ?string
    {
        return $this->linenetamt;
    }

    public function setLinenetamt(string $linenetamt): self
    {
        $this->linenetamt = $linenetamt;

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

    public function getCActivityId(): ?int
    {
        return $this->c_activity_id;
    }

    public function setCActivityId(int $c_activity_id): self
    {
        $this->c_activity_id = $c_activity_id;

        return $this;
    }

    public function getCBpartnerId(): ?int
    {
        return $this->c_bpartner_id;
    }

    public function setCBpartnerId(int $c_bpartner_id): self
    {
        $this->c_bpartner_id = $c_bpartner_id;

        return $this;
    }

    public function getCBpartnerLocationId(): ?int
    {
        return $this->c_bpartner_location_id;
    }

    public function setCBpartnerLocationId(int $c_bpartner_location_id): self
    {
        $this->c_bpartner_location_id = $c_bpartner_location_id;

        return $this;
    }

    public function getMWarehouseId(): ?int
    {
        return $this->m_warehouse_id;
    }

    public function setMWarehouseId(int $m_warehouse_id): self
    {
        $this->m_warehouse_id = $m_warehouse_id;

        return $this;
    }

    public function getCUomId(): ?int
    {
        return $this->c_uom_id;
    }

    public function setCUomId(int $c_uom_id): self
    {
        $this->c_uom_id = $c_uom_id;

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

    public function getCreatedby(): ?int
    {
        return $this->createdby;
    }

    public function setCreatedby(int $createdby): self
    {
        $this->createdby = $createdby;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getUpdatedby(): ?int
    {
        return $this->updatedby;
    }

    public function setUpdatedby(int $updatedby): self
    {
        $this->updatedby = $updatedby;

        return $this;
    }

    public function getDateordered(): ?\DateTimeInterface
    {
        return $this->dateordered;
    }

    public function setDateordered(\DateTimeInterface $dateordered): self
    {
        $this->dateordered = $dateordered;

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
}
