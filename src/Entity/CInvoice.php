<?php

namespace App\Entity;

use App\Repository\CInvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CInvoiceRepository::class)
 * @ORM\Table(name="c_invoice")
 */
class CInvoice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $c_invoice_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_client_id;

    /**
     * @ORM\ManyToOne(targetEntity=AdOrg::class, inversedBy="c_invoices")
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
     * @ORM\Column(type="string", length=1)
     */
    private $issotrx = 'Y';

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $documentno;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $docstatus;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $docaction;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $processing;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $processed = 'N';

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $posted = 'N';

    /**
     * @ORM\ManyToOne(targetEntity=CDoctype::class)
     * @ORM\JoinColumn(referencedColumnName="c_doctype_id")
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $c_order_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isapproved = 'Y';

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $istransferred = 'N';

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isprinted = 'N';

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $salesrep_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateinvoiced;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateacct;

    /**
     * @ORM\ManyToOne(targetEntity=CBpartner::class, inversedBy="c_invoices")
     * @ORM\JoinColumn(referencedColumnName="c_bpartner_id")
     */
    private $c_bpartner;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_bpartner_id;

    /**
     * @ORM\ManyToOne(targetEntity=CBpartnerLocation::class)
     * @ORM\JoinColumn(referencedColumnName="c_bpartner_location_id")
     */
    private $c_bpartner_location;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_bpartner_location_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isdiscountprinted = 'Y';

    /**
     * @ORM\ManyToOne(targetEntity=CCurrency::class)
     * @ORM\JoinColumn(referencedColumnName="c_currency_id")
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
     * @ORM\JoinColumn(referencedColumnName="c_paymentterm_id")
     */
    private $c_paymentterm;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_paymentterm_id;

    /**
     * @ORM\Column(type="float")
     */
    private $totallines = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $grandtotal = 0;

    /**
     * @ORM\ManyToOne(targetEntity=MPricelist::class)
     * @ORM\JoinColumn(referencedColumnName="m_pricelist_id")
     */
    private $m_pricelist;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $m_pricelist_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $istaxincluded = 'N';

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $ispaid = 'N';

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $c_payment_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $sendemail = 'N';

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isselfservice = 'N';

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $c_conversiontype_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $ispayschedulevalid = 'N';

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isindispute = 'N';

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    private $c_invoice_uu;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $notificationnode = 0;

    /**
     * @ORM\OneToMany(targetEntity=CInvoiceline::class, mappedBy="c_invoice")
     */
    private $c_invoicelines;

    public function __construct()
    {
        $this->c_invoicelines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->getCInvoiceId();
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

    public function getPosted(): ?string
    {
        return $this->posted;
    }

    public function setPosted(string $posted): self
    {
        $this->posted = $posted;

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

    public function getCOrderId(): ?int
    {
        return $this->c_order_id;
    }

    public function setCOrderId(?int $c_order_id): self
    {
        $this->c_order_id = $c_order_id;

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

    public function getIsapproved(): ?string
    {
        return $this->isapproved;
    }

    public function setIsapproved(string $isapproved): self
    {
        $this->isapproved = $isapproved;

        return $this;
    }

    public function getIstransferred(): ?string
    {
        return $this->istransferred;
    }

    public function setIstransferred(string $istransferred): self
    {
        $this->istransferred = $istransferred;

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

    public function getSalesrepId(): ?int
    {
        return $this->salesrep_id;
    }

    public function setSalesrepId(?int $salesrep_id): self
    {
        $this->salesrep_id = $salesrep_id;

        return $this;
    }

    public function getDateinvoiced(): ?\DateTimeInterface
    {
        return $this->dateinvoiced;
    }

    public function setDateinvoiced(\DateTimeInterface $dateinvoiced): self
    {
        $this->dateinvoiced = $dateinvoiced;

        return $this;
    }

    public function getDateacct(): ?\DateTimeInterface
    {
        return $this->dateacct;
    }

    public function setDateacct(?\DateTimeInterface $dateacct): self
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

    public function getIsdiscountprinted(): ?string
    {
        return $this->isdiscountprinted;
    }

    public function setIsdiscountprinted(string $isdiscountprinted): self
    {
        $this->isdiscountprinted = $isdiscountprinted;

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

    public function getMPricelistId(): ?int
    {
        return $this->m_pricelist_id;
    }

    public function setMPricelistId(int $m_pricelist_id): self
    {
        $this->m_pricelist_id = $m_pricelist_id;

        return $this;
    }

    public function getIstaxincluded(): ?string
    {
        return $this->istaxincluded;
    }

    public function setIstaxincluded(string $istaxincluded): self
    {
        $this->istaxincluded = $istaxincluded;

        return $this;
    }

    public function getIspaid(): ?string
    {
        return $this->ispaid;
    }

    public function setIspaid(string $ispaid): self
    {
        $this->ispaid = $ispaid;

        return $this;
    }

    public function getCPaymentId(): ?int
    {
        return $this->c_payment_id;
    }

    public function setCPaymentId(?int $c_payment_id): self
    {
        $this->c_payment_id = $c_payment_id;

        return $this;
    }

    public function getSendemail(): ?string
    {
        return $this->sendemail;
    }

    public function setSendemail(string $sendemail): self
    {
        $this->sendemail = $sendemail;

        return $this;
    }

    public function getIsselfservice(): ?string
    {
        return $this->isselfservice;
    }

    public function setIsselfservice(string $isselfservice): self
    {
        $this->isselfservice = $isselfservice;

        return $this;
    }

    public function getCConversiontypeId(): ?int
    {
        return $this->c_conversiontype_id;
    }

    public function setCConversiontypeId(?int $c_conversiontype_id): self
    {
        $this->c_conversiontype_id = $c_conversiontype_id;

        return $this;
    }

    public function getIspayschedulevalid(): ?string
    {
        return $this->ispayschedulevalid;
    }

    public function setIspayschedulevalid(string $ispayschedulevalid): self
    {
        $this->ispayschedulevalid = $ispayschedulevalid;

        return $this;
    }

    public function getIsindispute(): ?string
    {
        return $this->isindispute;
    }

    public function setIsindispute(string $isindispute): self
    {
        $this->isindispute = $isindispute;

        return $this;
    }

    public function getCInvoiceUu(): ?string
    {
        return $this->c_invoice_uu;
    }

    public function setCInvoiceUu(?string $c_invoice_uu): self
    {
        $this->c_invoice_uu = $c_invoice_uu;

        return $this;
    }

    public function getNotificationnode(): ?string
    {
        return $this->notificationnode;
    }

    public function setNotificationnode(string $notificationnode): self
    {
        $this->notificationnode = $notificationnode;

        return $this;
    }

    /**
     * @return Collection|CInvoiceline[]
     */
    public function getCInvoicelines(): Collection
    {
        return $this->c_invoicelines;
    }

    public function addCInvoiceline(CInvoiceline $cInvoiceline): self
    {
        if (!$this->c_invoicelines->contains($cInvoiceline)) {
            $this->c_invoicelines[] = $cInvoiceline;
            $cInvoiceline->setCInvoice($this);
        }

        return $this;
    }

    public function removeCInvoiceline(CInvoiceline $cInvoiceline): self
    {
        if ($this->c_invoicelines->removeElement($cInvoiceline)) {
            // set the owning side to null (unless already changed)
            if ($cInvoiceline->getCInvoice() === $this) {
                $cInvoiceline->setCInvoice(null);
            }
        }

        return $this;
    }
}
