<?php

namespace App\Entity\Main;

use App\Repository\Main\CBpartnerRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CBpartnerRepository::class)
 * @ORM\Table(name="c_bpartner")
 */
class CBpartner
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $c_bpartner_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $taxid;

    /**
     * @ORM\OneToMany(targetEntity=COrder::class, mappedBy="c_bpartner")
     */
    private $orders;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $iscustomer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\OneToMany(targetEntity=CBpartnerLocation::class, mappedBy="c_bpartner")
     */
    private $c_bpartner_location;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $issalesrep;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $ismatriz;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $issummary;

    /**
     * @ORM\OneToMany(targetEntity=CInvoice::class, mappedBy="c_bpartner")
     */
    private $c_invoices;

    /**
     * @ORM\OneToMany(targetEntity=MInout::class, mappedBy="c_bpartner")
     */
    private $m_inouts;

    /**
     * @ORM\OneToMany(targetEntity=SmSalesRep::class, mappedBy="c_bpartner")
     */
    private $smSalesReps;

    /**
     * @ORM\OneToMany(targetEntity=MRequisition::class, mappedBy="c_bpartner")
     */
    private $m_requisitions;

    /**
     * @ORM\Column(type="float")
     */
    private $totalopenbalance;

    /**
     * @ORM\Column(type="datetime")
     */
    private $firstsale;

    /**
     * @ORM\Column(type="float")
     */
    private $so_creditlimit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->c_bpartner_location = new ArrayCollection();
        $this->c_invoices = new ArrayCollection();
        $this->m_inouts = new ArrayCollection();
        $this->smSalesReps = new ArrayCollection();
        $this->m_requisitions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->getCBpartnerId();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTaxid(): ?string
    {
        return $this->taxid;
    }

    public function setTaxid(string $taxid): self
    {
        $this->taxid = $taxid;

        return $this;
    }

    /**
     * @return Collection|COrder[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(COrder $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCBpartner($this);
        }

        return $this;
    }

    public function removeOrder(COrder $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCBpartner() === $this) {
                $order->setCBpartner(null);
            }
        }

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

    public function getIscustomer(): ?string
    {
        return $this->iscustomer;
    }

    public function setIscustomer(string $iscustomer): self
    {
        $this->iscustomer = $iscustomer;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection|CBpartnerLocation[]
     */
    public function getCBpartnerLocation(): Collection
    {
        return $this->c_bpartner_location;
    }

    public function addCBpartnerLocation(CBpartnerLocation $cBpartnerLocation): self
    {
        if (!$this->c_bpartner_location->contains($cBpartnerLocation)) {
            $this->c_bpartner_location[] = $cBpartnerLocation;
            $cBpartnerLocation->setCBpartner($this);
        }

        return $this;
    }

    public function removeCBpartnerLocation(CBpartnerLocation $cBpartnerLocation): self
    {
        if ($this->c_bpartner_location->removeElement($cBpartnerLocation)) {
            // set the owning side to null (unless already changed)
            if ($cBpartnerLocation->getCBpartner() === $this) {
                $cBpartnerLocation->setCBpartner(null);
            }
        }

        return $this;
    }

    public function getIssalesrep(): ?bool
    {
        return ( $this->issalesrep == 'Y' );
    }

    public function setIssalesrep(string $issalesrep): self
    {
        $this->issalesrep = $issalesrep;

        return $this;
    }

    public function getIsmatriz(): ?string
    {
        return $this->ismatriz;
    }

    public function setIsmatriz(string $ismatriz): self
    {
        $this->ismatriz = $ismatriz;

        return $this;
    }

    public function getIssummary(): ?string
    {
        return $this->issummary;
    }

    public function setIssummary(string $issummary): self
    {
        $this->issummary = $issummary;

        return $this;
    }

    /**
     * @return Collection|CInvoice[]
     */
    public function getCInvoices(): Collection
    {
        return $this->c_invoices;
    }

    public function addCInvoice(CInvoice $cInvoice): self
    {
        if (!$this->c_invoices->contains($cInvoice)) {
            $this->c_invoices[] = $cInvoice;
            $cInvoice->setCBpartner($this);
        }

        return $this;
    }

    public function removeCInvoice(CInvoice $cInvoice): self
    {
        if ($this->c_invoices->removeElement($cInvoice)) {
            // set the owning side to null (unless already changed)
            if ($cInvoice->getCBpartner() === $this) {
                $cInvoice->setCBpartner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MInout[]
     */
    public function getMInouts(): Collection
    {
        return $this->m_inouts;
    }

    public function addMInout(MInout $mInout): self
    {
        if (!$this->m_inouts->contains($mInout)) {
            $this->m_inouts[] = $mInout;
            $mInout->setCBpartner($this);
        }

        return $this;
    }

    public function removeMInout(MInout $mInout): self
    {
        if ($this->m_inouts->removeElement($mInout)) {
            // set the owning side to null (unless already changed)
            if ($mInout->getCBpartner() === $this) {
                $mInout->setCBpartner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SmSalesRep[]
     */
    public function getSmSalesReps(): Collection
    {
        return $this->smSalesReps;
    }

    /**
     * @return Collection|MRequisition[]
     */
    public function getMRequisitions(): Collection
    {
        return $this->m_requisitions;
    }

    public function addMRequisition(MRequisition $mRequisition): self
    {
        if (!$this->m_requisitions->contains($mRequisition)) {
            $this->m_requisitions[] = $mRequisition;
            $mRequisition->setCBpartner($this);
        }

        return $this;
    }

    public function removeMRequisition(MRequisition $mRequisition): self
    {
        if ($this->m_requisitions->removeElement($mRequisition)) {
            // set the owning side to null (unless already changed)
            if ($mRequisition->getCBpartner() === $this) {
                $mRequisition->setCBpartner(null);
            }
        }

        return $this;
    }

    public function getTotalopenbalance(): ?float
    {
        return $this->totalopenbalance;
    }

    public function setTotalopenbalance(float $totalopenbalance): self
    {
        $this->totalopenbalance = $totalopenbalance;

        return $this;
    }

    public function getFirstsale(): ?DateTime
    {
        return $this->firstsale;
    }

    public function setFirstsale(DateTime $firstsale): self
    {
        $this->firstsale = $firstsale;

        return $this;
    }

    public function getSoCreditlimit(): ?float
    {
        return $this->so_creditlimit;
    }

    public function setSoCreditlimit(float $so_creditlimit): self
    {
        $this->so_creditlimit = $so_creditlimit;

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
}
