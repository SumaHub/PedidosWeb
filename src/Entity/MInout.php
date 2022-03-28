<?php

namespace App\Entity;

use App\Repository\MInoutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MInoutRepository::class)
 * @ORM\Table(name="m_inout")
 */
class MInout
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $m_inout_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_client_id;

    /**
     * @ORM\ManyToOne(targetEntity=AdOrg::class, inversedBy="m_inouts")
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
     * @ORM\Column(type="string", length=30)
     */
    private $documentno;

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
    private $posted;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $processing;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $processed;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $c_order_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateordered;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isprinted;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $movementtype;

    /**
     * @ORM\Column(type="datetime")
     */
    private $movementdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateacct;

    /**
     * @ORM\ManyToOne(targetEntity=CBpartner::class, inversedBy="m_inouts")
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
     * @ORM\ManyToOne(targetEntity=MWarehouse::class)
     * @ORM\JoinColumn(referencedColumnName="m_warehouse_id")
     */
    private $m_warehouse;

    /**
     * @ORM\Column(type="integer")
     */
    private $m_warehouse_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $deliveryrule;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $freightcostrule;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $freightamt;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $deliveryviarule;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $priorityrule;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $salesrep_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $pickdate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $shipdate;

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    private $m_inout_uu;

    /**
     * @ORM\OneToMany(targetEntity=MInoutline::class, mappedBy="m_inout")
     */
    private $m_inoutlines;

    public function __construct()
    {
        $this->m_inoutlines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->getMInoutId();
    }

    public function getMInoutId(): ?int
    {
        return $this->m_inout_id;
    }

    public function setMInoutId(int $m_inout_id): self
    {
        $this->m_inout_id = $m_inout_id;

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

    public function getPosted(): ?string
    {
        return $this->posted;
    }

    public function setPosted(string $posted): self
    {
        $this->posted = $posted;

        return $this;
    }

    public function getProcessing(): ?string
    {
        return $this->processing;
    }

    public function setProcessing(?string $processing): self
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getDateordered(): ?\DateTimeInterface
    {
        return $this->dateordered;
    }

    public function setDateordered(?\DateTimeInterface $dateordered): self
    {
        $this->dateordered = $dateordered;

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

    public function getMovementtype(): ?string
    {
        return $this->movementtype;
    }

    public function setMovementtype(string $movementtype): self
    {
        $this->movementtype = $movementtype;

        return $this;
    }

    public function getMovementdate(): ?\DateTimeInterface
    {
        return $this->movementdate;
    }

    public function setMovementdate(\DateTimeInterface $movementdate): self
    {
        $this->movementdate = $movementdate;

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

    public function getMWarehouse(): ?MWarehouse
    {
        return $this->m_warehouse;
    }

    public function setMWarehouse(?MWarehouse $m_warehouse): self
    {
        $this->m_warehouse = $m_warehouse;

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

    public function getFreightamt(): ?float
    {
        return $this->freightamt;
    }

    public function setFreightamt(?float $freightamt): self
    {
        $this->freightamt = $freightamt;

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

    public function getSalesrepId(): ?int
    {
        return $this->salesrep_id;
    }

    public function setSalesrepId(?int $salesrep_id): self
    {
        $this->salesrep_id = $salesrep_id;

        return $this;
    }

    public function getPickdate(): ?\DateTimeInterface
    {
        return $this->pickdate;
    }

    public function setPickdate(?\DateTimeInterface $pickdate): self
    {
        $this->pickdate = $pickdate;

        return $this;
    }

    public function getShipdate(): ?\DateTimeInterface
    {
        return $this->shipdate;
    }

    public function setShipdate(?\DateTimeInterface $shipdate): self
    {
        $this->shipdate = $shipdate;

        return $this;
    }

    public function getMInoutUu(): ?string
    {
        return $this->m_inout_uu;
    }

    public function setMInoutUu(?string $m_inout_uu): self
    {
        $this->m_inout_uu = $m_inout_uu;

        return $this;
    }

    /**
     * @return Collection|MInoutline[]
     */
    public function getMInoutlines(): Collection
    {
        return $this->m_inoutlines;
    }

    public function addMInoutline(MInoutline $mInoutline): self
    {
        if (!$this->m_inoutlines->contains($mInoutline)) {
            $this->m_inoutlines[] = $mInoutline;
            $mInoutline->setMInout($this);
        }

        return $this;
    }

    public function removeMInoutline(MInoutline $mInoutline): self
    {
        if ($this->m_inoutlines->removeElement($mInoutline)) {
            // set the owning side to null (unless already changed)
            if ($mInoutline->getMInout() === $this) {
                $mInoutline->setMInout(null);
            }
        }

        return $this;
    }
}
