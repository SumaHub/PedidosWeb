<?php

namespace App\Entity\Main;

use App\Repository\Main\MRequisitionlineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MRequisitionlineRepository::class)
 * @ORM\Table(name="M_RequisitionLine")
 */
class MRequisitionline
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $m_requisitionline_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_client_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_org_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

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
     * @ORM\Column(type="integer")
     */
    private $line;

    /**
     * @ORM\Column(type="integer")
     */
    private $m_requisition_id;

    /**
     * @ORM\Column(type="float")
     */
    private $qty;

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
     * @ORM\Column(type="float")
     */
    private $priceactual;

    /**
     * @ORM\Column(type="float")
     */
    private $linenetamt;

    /**
     * @ORM\Column(type="string", length=36)
     */
    private $m_requisitionline_uu;

    /**
     * @ORM\Column(type="float")
     */
    private $discount;

    /**
     * @ORM\Column(type="float")
     */
    private $totalpriceprom;

    /**
     * @ORM\Column(type="float")
     */
    private $desiredprice;

    /**
     * @ORM\Column(type="float")
     */
    private $totalpricebreak;

    /**
     * @ORM\Column(type="float")
     */
    private $sumpriceprom;

    /**
     * @ORM\Column(type="float")
     */
    private $sumpricebreak;

    /**
     * @ORM\Column(type="float")
     */
    private $totalprombreak;

    /**
     * @ORM\Column(type="float")
     */
    private $porcentprombreak;

    /**
     * @ORM\Column(type="float")
     */
    private $priceprecision;

    /**
     * @ORM\Column(type="float")
     */
    private $qtyplan;

    /**
     * @ORM\Column(type="float")
     */
    private $qtyplan2;

    /**
     * @ORM\Column(type="float")
     */
    private $priceoverride;

    /**
     * @ORM\Column(type="float")
     */
    private $priceoverride2;

    /**
     * @ORM\Column(type="string", length=600, nullable=true)
     */
    private $information;

    /**
     * @ORM\Column(type="string", length=600, nullable=true)
     */
    private $information2;

    /**
     * @ORM\Column(type="float")
     */
    private $pricestdamt;

    /**
     * @ORM\Column(type="float")
     */
    private $pricelist;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_uom_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_bpartner_id;

    public function getId(): ?int
    {
        return $this->getMRequisitionlineId();
    }

    public function getMRequisitionlineId(): ?int
    {
        return $this->m_requisitionline_id;
    }

    public function setMRequisitionlineId(int $m_requisitionline_id): self
    {
        $this->m_requisitionline_id = $m_requisitionline_id;

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

    public function getIsactive(): ?string
    {
        return $this->isactive;
    }

    public function setIsactive(string $isactive): self
    {
        $this->isactive = $isactive;

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

    public function getLine(): ?int
    {
        return $this->line;
    }

    public function setLine(int $line): self
    {
        $this->line = $line;

        return $this;
    }

    public function getMRequisitionId(): ?int
    {
        return $this->m_requisition_id;
    }

    public function setMRequisitionId(int $m_requisition_id): self
    {
        $this->m_requisition_id = $m_requisition_id;

        return $this;
    }

    public function getQty(): ?float
    {
        return $this->qty;
    }

    public function setQty(float $qty): self
    {
        $this->qty = $qty;

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

    public function setMProductId(?int $m_product_id): self
    {
        $this->m_product_id = $m_product_id;

        return $this;
    }

    public function getPriceactual(): ?float
    {
        return $this->priceactual;
    }

    public function setPriceactual(float $priceactual): self
    {
        $this->priceactual = $priceactual;

        return $this;
    }

    public function getLinenetamt(): ?float
    {
        return $this->linenetamt;
    }

    public function setLinenetamt(float $linenetamt): self
    {
        $this->linenetamt = $linenetamt;

        return $this;
    }

    public function getMRequisitionlineUu(): ?string
    {
        return $this->m_requisitionline_uu;
    }

    public function setMRequisitionlineUu(string $m_requisitionline_uu): self
    {
        $this->m_requisitionline_uu = $m_requisitionline_uu;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getTotalpriceprom(): ?float
    {
        return $this->totalpriceprom;
    }

    public function setTotalpriceprom(float $totalpriceprom): self
    {
        $this->totalpriceprom = $totalpriceprom;

        return $this;
    }

    public function getDesiredprice(): ?float
    {
        return $this->desiredprice;
    }

    public function setDesiredprice(float $desiredprice): self
    {
        $this->desiredprice = $desiredprice;

        return $this;
    }

    public function getTotalpricebreak(): ?float
    {
        return $this->totalpricebreak;
    }

    public function setTotalpricebreak(?float $totalpricebreak): self
    {
        $this->totalpricebreak = $totalpricebreak;

        return $this;
    }

    public function getSumpriceprom(): ?float
    {
        return $this->sumpriceprom;
    }

    public function setSumpriceprom(float $sumpriceprom): self
    {
        $this->sumpriceprom = $sumpriceprom;

        return $this;
    }

    public function getSumpricebreak(): ?float
    {
        return $this->sumpricebreak;
    }

    public function setSumpricebreak(?float $sumpricebreak): self
    {
        $this->sumpricebreak = $sumpricebreak;

        return $this;
    }

    public function getTotalprombreak(): ?float
    {
        return $this->totalprombreak;
    }

    public function setTotalprombreak(?float $totalprombreak): self
    {
        $this->totalprombreak = $totalprombreak;

        return $this;
    }

    public function getPorcentprombreak(): ?float
    {
        return $this->porcentprombreak;
    }

    public function setPorcentprombreak(?float $porcentprombreak): self
    {
        $this->porcentprombreak = $porcentprombreak;

        return $this;
    }

    public function getPriceprecision(): ?float
    {
        return $this->priceprecision;
    }

    public function setPriceprecision(?float $priceprecision): self
    {
        $this->priceprecision = $priceprecision;

        return $this;
    }

    public function getQtyplan(): ?float
    {
        return $this->qtyplan;
    }

    public function setQtyplan(?float $qtyplan): self
    {
        $this->qtyplan = $qtyplan;

        return $this;
    }

    public function getQtyplan2(): ?float
    {
        return $this->qtyplan2;
    }

    public function setQtyplan2(?float $qtyplan2): self
    {
        $this->qtyplan2 = $qtyplan2;

        return $this;
    }

    public function getPriceoverride(): ?float
    {
        return $this->priceoverride;
    }

    public function setPriceoverride(float $priceoverride): self
    {
        $this->priceoverride = $priceoverride;

        return $this;
    }

    public function getPriceoverride2(): ?float
    {
        return $this->priceoverride2;
    }

    public function setPriceoverride2(?float $priceoverride2): self
    {
        $this->priceoverride2 = $priceoverride2;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(?string $information): self
    {
        $this->information = $information;

        return $this;
    }

    public function getInformation2(): ?string
    {
        return $this->information2;
    }

    public function setInformation2(?string $information2): self
    {
        $this->information2 = $information2;

        return $this;
    }

    public function getPricestdamt(): ?float
    {
        return $this->pricestdamt;
    }

    public function setPricestdamt(?float $pricestdamt): self
    {
        $this->pricestdamt = $pricestdamt;

        return $this;
    }

    public function getPricelist(): ?string
    {
        return $this->pricelist;
    }

    public function setPricelist(?string $pricelist): self
    {
        $this->pricelist = $pricelist;

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

    public function getCBpartnerId(): ?int
    {
        return $this->c_bpartner_id;
    }

    public function setCBpartnerId(int $c_bpartner_id): self
    {
        $this->c_bpartner_id = $c_bpartner_id;

        return $this;
    }
}
