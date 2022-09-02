<?php

namespace App\Entity\Main;

use App\Repository\Main\CInvoicelineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CInvoicelineRepository::class)
 * @ORM\Table(name="c_invoiceline")
 */
class CInvoiceline
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $c_invoiceline_id;

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
    private $isactive = 'Y';

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
     * @ORM\ManyToOne(targetEntity=CInvoice::class, inversedBy="c_invoicelines")
     * @ORM\JoinColumn(referencedColumnName="c_invoice_id")
     */
    private $c_invoice;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_invoice_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $line;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=MProduct::class)
     * @ORM\JoinColumn(referencedColumnName="m_product_id")
     */
    private $m_product;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $m_product_id;

    /**
     * @ORM\Column(type="float")
     */
    private $qtyinvoiced = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $pricelist = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $priceactual = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $pricelimit = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $linenetamt = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $c_uom_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $c_tax_id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $taxamt = 0;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isdescription = 'N';

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isprinted = 'Y';

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $linetotalamt = 0;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $processed = 'N';

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qtyentered = 1;

    /**
     * @ORM\Column(type="float")
     */
    private $priceentered = 0;

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    private $c_invoiceline_uu;

    public function getId(): ?int
    {
        return $this->getCInvoicelineId();
    }

    public function getCInvoicelineId(): ?int
    {
        return $this->c_invoiceline_id;
    }

    public function setCInvoicelineId(int $c_invoiceline_id): self
    {
        $this->c_invoiceline_id = $c_invoiceline_id;

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

    public function getCInvoice(): ?CInvoice
    {
        return $this->c_invoice;
    }

    public function setCInvoice(?CInvoice $c_invoice): self
    {
        $this->c_invoice = $c_invoice;

        return $this;
    }

    public function getCInvoiceId(): ?int
    {
        return $this->c_invoice_id;
    }

    public function setCInvoiceId(int $c_invoice_id): self
    {
        $this->c_invoice_id = $c_invoice_id;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getQtyinvoiced(): ?float
    {
        return $this->qtyinvoiced;
    }

    public function setQtyinvoiced(float $qtyinvoiced): self
    {
        $this->qtyinvoiced = $qtyinvoiced;

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

    public function getPriceactual(): ?float
    {
        return $this->priceactual;
    }

    public function setPriceactual(float $priceactual): self
    {
        $this->priceactual = $priceactual;

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

    public function getLinenetamt(): ?float
    {
        return $this->linenetamt;
    }

    public function setLinenetamt(float $linenetamt): self
    {
        $this->linenetamt = $linenetamt;

        return $this;
    }

    public function getCUomId(): ?int
    {
        return $this->c_uom_id;
    }

    public function setCUomId(?int $c_uom_id): self
    {
        $this->c_uom_id = $c_uom_id;

        return $this;
    }

    public function getCTaxId(): ?int
    {
        return $this->c_tax_id;
    }

    public function setCTaxId(?int $c_tax_id): self
    {
        $this->c_tax_id = $c_tax_id;

        return $this;
    }

    public function getTaxamt(): ?float
    {
        return $this->taxamt;
    }

    public function setTaxamt(?float $taxamt): self
    {
        $this->taxamt = $taxamt;

        return $this;
    }

    public function getIsdescription(): ?string
    {
        return $this->isdescription;
    }

    public function setIsdescription(string $isdescription): self
    {
        $this->isdescription = $isdescription;

        return $this;
    }

    public function getIsprinted(): ?string
    {
        return $this->isprinted;
    }

    public function setIsprinted(string $isprinted): self
    {
        $this->isprinted = $isprinted;

        return $this;
    }

    public function getLinetotalamt(): ?float
    {
        return $this->linetotalamt;
    }

    public function setLinetotalamt(?float $linetotalamt): self
    {
        $this->linetotalamt = $linetotalamt;

        return $this;
    }

    public function getProcessed(): ?string
    {
        return $this->processed;
    }

    public function setProcessed(string $processed): self
    {
        $this->processed = $processed;

        return $this;
    }

    public function getQtyentered(): ?float
    {
        return $this->qtyentered;
    }

    public function setQtyentered(?float $qtyentered): self
    {
        $this->qtyentered = $qtyentered;

        return $this;
    }

    public function getPriceentered(): ?float
    {
        return $this->priceentered;
    }

    public function setPriceentered(float $priceentered): self
    {
        $this->priceentered = $priceentered;

        return $this;
    }

    public function getCInvoicelineUu(): ?string
    {
        return $this->c_invoiceline_uu;
    }

    public function setCInvoicelineUu(?string $c_invoiceline_uu): self
    {
        $this->c_invoiceline_uu = $c_invoiceline_uu;

        return $this;
    }
}
