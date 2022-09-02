<?php

namespace App\Entity\Main;

use App\Repository\Main\MRequisitionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MRequisitionRepository::class)
 * @ORM\Table(name="M_Requisition")
 */
class MRequisition
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $m_requisition_id;

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
     * @ORM\Column(type="string", length=30)
     */
    private $documentno;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $help;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $m_warehouse_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isapproved;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $priorityrule;

    /**
     * @ORM\Column(type="datetime")
     */
    private $daterequired;

    /**
     * @ORM\Column(type="float")
     */
    private $totallines;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $docaction;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $docstatus;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $processed;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $posted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datedoc;

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
     * @ORM\Column(type="string", length=36)
     */
    private $m_requisition_uu;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isapproved2;

    /**
     * @ORM\ManyToOne(targetEntity=CBpartner::class, inversedBy="m_requisitions")
     * @ORM\JoinColumn(referencedColumnName="c_bpartner_id", nullable=false)
     */
    private $c_bpartner;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $c_bpartner_id;

    /**
     * @ORM\ManyToOne(targetEntity=CBpartnerLocation::class)
     * @ORM\JoinColumn(referencedColumnName="c_bpartner_location_id", nullable=false)
     */
    private $c_bpartner_location;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $c_bpartner_location_id;

    /**
     * @ORM\ManyToOne(targetEntity=AdUser::class)
     * @ORM\JoinColumn(name="salesrep_id", referencedColumnName="ad_user_id")
     */
    private $salesrep;

    /**
     * @ORM\Column(type="integer")
     */
    private $salesrep_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $sm_marca_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isquatation;

    /**
     * @ORM\ManyToOne(targetEntity=CPaymentterm::class)
     * @ORM\JoinColumn(referencedColumnName="c_paymentterm_id", nullable=false)
     */
    private $c_paymentterm;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $c_paymentterm_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $sm_document_int;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isapproved3;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $processing;

    /**
     * @ORM\Column(type="float")
     */
    private $processedon;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_user_id;

    public function getId(): ?int
    {
        return $this->getMRequisitionId();
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

    public function getDocumentno(): ?string
    {
        return $this->documentno;
    }

    public function setDocumentno(string $documentno): self
    {
        $this->documentno = $documentno;

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

    public function getHelp(): ?string
    {
        return $this->help;
    }

    public function setHelp(?string $help): self
    {
        $this->help = $help;

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

    public function getMWarehouseId(): ?int
    {
        return $this->m_warehouse_id;
    }

    public function setMWarehouseId(int $m_warehouse_id): self
    {
        $this->m_warehouse_id = $m_warehouse_id;

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

    public function getPriorityrule(): ?string
    {
        return $this->priorityrule;
    }

    public function setPriorityrule(string $priorityrule): self
    {
        $this->priorityrule = $priorityrule;

        return $this;
    }

    public function getDaterequired(): ?\DateTimeInterface
    {
        return $this->daterequired;
    }

    public function setDaterequired(\DateTimeInterface $daterequired): self
    {
        $this->daterequired = $daterequired;

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

    public function getDocaction(): ?string
    {
        return $this->docaction;
    }

    public function setDocaction(string $docaction): self
    {
        $this->docaction = $docaction;

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

    public function getDatedoc(): ?\DateTimeInterface
    {
        return $this->datedoc;
    }

    public function setDatedoc(\DateTimeInterface $datedoc): self
    {
        $this->datedoc = $datedoc;

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

    public function getMRequisitionUu(): ?string
    {
        return $this->m_requisition_uu;
    }

    public function setMRequisitionUu(string $m_requisition_uu): self
    {
        $this->m_requisition_uu = $m_requisition_uu;

        return $this;
    }

    public function getIsapproved2(): ?string
    {
        return $this->isapproved2;
    }

    public function setIsapproved2(string $isapproved2): self
    {
        $this->isapproved2 = $isapproved2;

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

    public function setCBpartnerId(?int $c_bpartner_id): self
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

    public function setCBpartnerLocationId(?int $c_bpartner_location_id): self
    {
        $this->c_bpartner_location_id = $c_bpartner_location_id;

        return $this;
    }

    public function getSalesrep(): ?AdUser
    {
        return $this->salesrep;
    }

    public function setSalesrep(?AdUser $salesrep): self
    {
        $this->salesrep = $salesrep;

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

    public function getSmMarcaId(): ?int
    {
        return $this->sm_marca_id;
    }

    public function setSmMarcaId(int $sm_marca_id): self
    {
        $this->sm_marca_id = $sm_marca_id;

        return $this;
    }

    public function getIsquatation(): ?bool
    {
        return ($this->isquatation === 'Y');
    }

    public function setIsquatation(string $isquatation): self
    {
        $this->isquatation = $isquatation;

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

    public function setCPaymenttermId(?int $c_paymentterm_id): self
    {
        $this->c_paymentterm_id = $c_paymentterm_id;

        return $this;
    }

    public function getSmDocumentInt(): ?string
    {
        return $this->sm_document_int;
    }

    public function setSmDocumentInt(string $sm_document_int): self
    {
        $this->sm_document_int = $sm_document_int;

        return $this;
    }

    public function getIsapproved3(): ?string
    {
        return $this->isapproved3;
    }

    public function setIsapproved3(string $isapproved3): self
    {
        $this->isapproved3 = $isapproved3;

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

    public function getProcessedon(): ?float
    {
        return $this->processedon;
    }

    public function setProcessedon(float $processedon): self
    {
        $this->processedon = $processedon;

        return $this;
    }

    public function getAdUserId(): ?int
    {
        return $this->ad_user_id;
    }

    public function setAdUserId(int $ad_user_id): self
    {
        $this->ad_user_id = $ad_user_id;

        return $this;
    }
}
