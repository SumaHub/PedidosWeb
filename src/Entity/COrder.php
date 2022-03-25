<?php

namespace App\Entity;

use App\Repository\COrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=COrderRepository::class)
 * @ORM\Table(name="C_Order")
 */
class COrder
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $c_order_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_client_id;

    /**
     * @ORM\ManyToOne(targetEntity=AdOrg::class, inversedBy="c_orders")
     * @ORM\JoinColumn(referencedColumnName="ad_org_id")
     */
    private $ad_org;

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
     * @ORM\Column(type="string", length=1)
     */
    private $issotrx;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $documentno;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $docstatus;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $docaction;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $processing;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $processed;

    /**
     * @ORM\ManyToOne(targetEntity=CDoctype::class)
     * @ORM\JoinColumn(referencedColumnName="c_doctype_id", nullable=false)
     */
    private $c_doctype;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_doctype_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_doctypetarget_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isapproved;

    /**
     * @ORM\Column(type="integer")
     */
    private $salesrep_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateordered;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateacct;

    /**
     * @ORM\ManyToOne(targetEntity=CBpartner::class, inversedBy="orders")
     * @ORM\JoinColumn(referencedColumnName="c_bpartner_id", nullable=false)
     */
    private $c_bpartner;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_bpartner_id;

    /**
     * @ORM\ManyToOne(targetEntity=CBpartnerLocation::class, inversedBy="c_order")
     * @ORM\JoinColumn(referencedColumnName="c_bpartner_location_id", nullable=false)
     */
    private $c_bpartner_location;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_bpartner_location_id;

    /**
     * @ORM\ManyToOne(targetEntity=CCurrency::class)
     * @ORM\JoinColumn(referencedColumnName="c_currency_id", nullable=false)
     */
    private $c_currency;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_currency_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $paymentrule;

    /**
     * @ORM\ManyToOne(targetEntity=CPaymentterm::class)
     * @ORM\JoinColumn(referencedColumnName="c_paymentterm_id", nullable=false)
     */
    private $c_paymentterm;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_paymentterm_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $invoicerule;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $deliveryrule;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $freightcostrule;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $deliveryviarule;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $priorityrule;

    /**
     * @ORM\Column(type="float")
     */
    private $totallines;

    /**
     * @ORM\Column(type="float")
     */
    private $grandtotal;

    /**
     * @ORM\Column(type="integer")
     */
    private $m_warehouse_id;

    /**
     * @ORM\ManyToOne(targetEntity=MPricelist::class)
     * @ORM\JoinColumn(referencedColumnName="m_pricelist_id", nullable=false)
     */
    private $m_pricelist;

    /**
     * @ORM\Column(type="integer")
     */
    private $m_pricelist_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $c_order_uu;

    /**
     * @ORM\OneToMany(targetEntity=COrderline::class, mappedBy="c_order")
     */
    private $c_orderlines;

    public function __construct()
    {
        $this->c_orderlines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->getCOrderId();
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

    public function getAdClientId(): ?int
    {
        return $this->ad_client_id;
    }

    public function setAdClientId(int $ad_client_id): self
    {
        $this->ad_client_id = $ad_client_id;

        return $this;
    }

    public function getAdOrg(): ?AdOrg
    {
        return $this->ad_org;
    }

    public function setAdOrg(?AdOrg $ad_org): self
    {
        $this->ad_org = $ad_org;

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

    public function getIssotrx(): ?string
    {
        return $this->issotrx;
    }

    public function setIssotrx(string $issotrx): self
    {
        $this->issotrx = $issotrx;

        return $this;
    }

    public function getDocumentno(): ?string
    {
        return $this->documentno;
    }

    public function setDocumentno(string $documentno): self
    {
        $this->documentno = $documentno;

        return $this;
    }

    public function getDocstatus(): ?string
    {
        return $this->docstatus;
    }

    public function setDocstatus(string $docstatus): self
    {
        $this->docstatus = $docstatus;

        return $this;
    }

    public function getDocaction(): ?string
    {
        return $this->docaction;
    }

    public function setDocaction(string $docaction): self
    {
        $this->docaction = $docaction;

        return $this;
    }

    public function getProcessing(): ?string
    {
        return $this->processing;
    }

    public function setProcessing(string $processing): self
    {
        $this->processing = $processing;

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

    public function getCDoctype(): ?CDoctype
    {
        return $this->c_doctype;
    }

    public function setCDoctype(?CDoctype $c_doctype): self
    {
        $this->c_doctype = $c_doctype;

        return $this;
    }

    public function getCDoctypeId(): ?int
    {
        return $this->c_doctype_id;
    }

    public function setCDoctypeId(int $c_doctype_id): self
    {
        $this->c_doctype_id = $c_doctype_id;

        return $this;
    }

    public function getCDoctypetargetId(): ?int
    {
        return $this->c_doctypetarget_id;
    }

    public function setCDoctypetargetId(int $c_doctypetarget_id): self
    {
        $this->c_doctypetarget_id = $c_doctypetarget_id;

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

    public function getIsapproved(): ?string
    {
        return $this->isapproved;
    }

    public function setIsapproved(string $isapproved): self
    {
        $this->isapproved = $isapproved;

        return $this;
    }

    public function getSalesrepId(): ?int
    {
        return $this->salesrep_id;
    }

    public function setSalesrepId(int $salesrep_id): self
    {
        $this->salesrep_id = $salesrep_id;

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

    public function getDateacct(): ?\DateTimeInterface
    {
        return $this->dateacct;
    }

    public function setDateacct(\DateTimeInterface $dateacct): self
    {
        $this->dateacct = $dateacct;

        return $this;
    }

    public function getCBpartner(): ?CBpartner
    {
        return $this->c_bpartner;
    }

    public function setCBpartner(?CBpartner $c_bpartner): self
    {
        $this->c_bpartner = $c_bpartner;

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

    public function getCBpartnerLocation(): ?CBpartnerLocation
    {
        return $this->c_bpartner_location;
    }

    public function setCBpartnerLocation(?CBpartnerLocation $c_bpartner_location): self
    {
        $this->c_bpartner_location = $c_bpartner_location;

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

    public function getCCurrency(): ?CCurrency
    {
        return $this->c_currency;
    }

    public function setCCurrency(?CCurrency $c_currency): self
    {
        $this->c_currency = $c_currency;

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

    public function getPaymentrule(): ?string
    {
        return $this->paymentrule;
    }

    public function setPaymentrule(string $paymentrule): self
    {
        $this->paymentrule = $paymentrule;

        return $this;
    }

    public function getCPaymentterm(): ?CPaymentterm
    {
        return $this->c_paymentterm;
    }

    public function setCPaymentterm(?CPaymentterm $c_paymentterm): self
    {
        $this->c_paymentterm = $c_paymentterm;

        return $this;
    }

    public function getCPaymenttermId(): ?int
    {
        return $this->c_paymentterm_id;
    }

    public function setCPaymenttermId(int $c_paymentterm_id): self
    {
        $this->c_paymentterm_id = $c_paymentterm_id;

        return $this;
    }

    public function getInvoicerule(): ?string
    {
        return $this->invoicerule;
    }

    public function setInvoicerule(string $invoicerule): self
    {
        $this->invoicerule = $invoicerule;

        return $this;
    }

    public function getDeliveryrule(): ?string
    {
        return $this->deliveryrule;
    }

    public function setDeliveryrule(string $deliveryrule): self
    {
        $this->deliveryrule = $deliveryrule;

        return $this;
    }

    public function getFreightcostrule(): ?string
    {
        return $this->freightcostrule;
    }

    public function setFreightcostrule(string $freightcostrule): self
    {
        $this->freightcostrule = $freightcostrule;

        return $this;
    }

    public function getDeliveryviarule(): ?string
    {
        return $this->deliveryviarule;
    }

    public function setDeliveryviarule(string $deliveryviarule): self
    {
        $this->deliveryviarule = $deliveryviarule;

        return $this;
    }

    public function getPriorityrule(): ?string
    {
        return $this->priorityrule;
    }

    public function setPriorityrule(string $priorityrule): self
    {
        $this->priorityrule = $priorityrule;

        return $this;
    }

    public function getTotallines(): ?float
    {
        return $this->totallines;
    }

    public function setTotallines(float $totallines): self
    {
        $this->totallines = $totallines;

        return $this;
    }

    public function getGrandtotal(): ?float
    {
        return $this->grandtotal;
    }

    public function setGrandtotal(float $grandtotal): self
    {
        $this->grandtotal = $grandtotal;

        return $this;
    }

    public function getMPricelist(): ?MPricelist
    {
        return $this->m_pricelist;
    }

    public function setMPricelist(?MPricelist $m_pricelist): self
    {
        $this->m_pricelist = $m_pricelist;

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

    public function getMPricelistId(): ?int
    {
        return $this->m_pricelist_id;
    }

    public function setMPricelistId(int $m_pricelist_id): self
    {
        $this->m_pricelist_id = $m_pricelist_id;

        return $this;
    }

    public function getCOrderUu(): ?string
    {
        return $this->c_order_uu;
    }

    public function setCOrderUu(string $c_order_uu): self
    {
        $this->c_order_uu = $c_order_uu;

        return $this;
    }

    /**
     * @return Collection|COrderline[]
     */
    public function getCOrderline(): Collection
    {
        return $this->c_orderlines;
    }

    public function addCOrderline(COrderline $cOrderline): self
    {
        if (!$this->c_orderlines->contains($cOrderline)) {
            $this->c_orderlines[] = $cOrderline;
            $cOrderline->setCOrder($this);
        }

        return $this;
    }

    public function removeCOrderline(COrderline $cOrderline): self
    {
        if ($this->c_orderlines->removeElement($cOrderline)) {
            // set the owning side to null (unless already changed)
            if ($cOrderline->getCOrder() === $this) {
                $cOrderline->setCOrder(null);
            }
        }

        return $this;
    }
}
