<?php

namespace App\Entity;

use App\Repository\AdOrgRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdOrgRepository::class)
 * @ORM\Table(name="ad_org")
 */
class AdOrg
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $ad_org_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=AdOrginfo::class, mappedBy="ad_org")
     */
    private $ad_orginfo;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $issummary;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_client_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isselected;

    /**
     * @ORM\OneToMany(targetEntity=MWarehouse::class, mappedBy="ad_org")
     */
    private $m_warehouse;

    /**
     * @ORM\Column(type="integer")
     */
    private $createdby;

    /**
     * @ORM\Column(type="integer")
     */
    private $updatedby;

    /**
     * @ORM\OneToMany(targetEntity=COrder::class, mappedBy="ad_org")
     */
    private $c_orders;

    /**
     * @ORM\OneToMany(targetEntity=CInvoice::class, mappedBy="ad_org")
     */
    private $c_invoices;

    /**
     * @ORM\OneToMany(targetEntity=MInout::class, mappedBy="ad_org")
     */
    private $m_inouts;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->ad_orginfo = new ArrayCollection();
        $this->m_warehouse = new ArrayCollection();
        $this->c_orders = new ArrayCollection();
        $this->c_invoices = new ArrayCollection();
        $this->m_inouts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->getAdOrgId();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $order->setAdOrg($this);
        }

        return $this;
    }

    public function removeOrder(COrder $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getAdOrg() === $this) {
                $order->setAdOrg(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AdOrginfo[]
     */
    public function getAdOrginfo(): Collection
    {
        return $this->ad_orginfo;
    }

    public function addAdOrginfo(AdOrginfo $adOrginfo): self
    {
        if (!$this->ad_orginfo->contains($adOrginfo)) {
            $this->ad_orginfo[] = $adOrginfo;
            $adOrginfo->setAdOrg($this);
        }

        return $this;
    }

    public function removeAdOrginfo(AdOrginfo $adOrginfo): self
    {
        if ($this->ad_orginfo->removeElement($adOrginfo)) {
            // set the owning side to null (unless already changed)
            if ($adOrginfo->getAdOrg() === $this) {
                $adOrginfo->setAdOrg(null);
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

    public function getIssummary(): ?string
    {
        return $this->issummary;
    }

    public function setIssummary(string $issummary): self
    {
        $this->issummary = $issummary;

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

    public function getIsselected(): ?string
    {
        return $this->isselected;
    }

    public function setIsselected(string $isselected): self
    {
        $this->isselected = $isselected;

        return $this;
    }

    /**
     * @return Collection|MWarehouse[]
     */
    public function getMWarehouse(): Collection
    {
        return $this->m_warehouse;
    }

    public function addWarehouse(MWarehouse $m_warehouse): self
    {
        if (!$this->m_warehouse->contains($m_warehouse)) {
            $this->m_m_warehouse[] = $m_warehouse;
            $m_warehouse->setAdOrg($this);
        }

        return $this;
    }

    public function removeWarehouse(MWarehouse $m_warehouse): self
    {
        if ($this->m_warehouse->removeElement($m_warehouse)) {
            // set the owning side to null (unless already changed)
            if ($m_warehouse->getAdOrg() === $this) {
                $m_warehouse->setAdOrg(null);
            }
        }

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

    public function getUpdatedby(): ?int
    {
        return $this->updatedby;
    }

    public function setUpdatedby(int $updatedby): self
    {
        $this->updatedby = $updatedby;

        return $this;
    }

    /**
     * @return Collection|COrder[]
     */
    public function getCOrders(): Collection
    {
        return $this->c_orders;
    }

    public function addCOrder(COrder $cOrder): self
    {
        if (!$this->c_orders->contains($cOrder)) {
            $this->c_orders[] = $cOrder;
            $cOrder->setAdOrg($this);
        }

        return $this;
    }

    public function removeCOrder(COrder $cOrder): self
    {
        if ($this->c_orders->removeElement($cOrder)) {
            // set the owning side to null (unless already changed)
            if ($cOrder->getAdOrg() === $this) {
                $cOrder->setAdOrg(null);
            }
        }

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
            $cInvoice->setAdOrg($this);
        }

        return $this;
    }

    public function removeCInvoice(CInvoice $cInvoice): self
    {
        if ($this->c_invoices->removeElement($cInvoice)) {
            // set the owning side to null (unless already changed)
            if ($cInvoice->getAdOrg() === $this) {
                $cInvoice->setAdOrg(null);
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
            $mInout->setAdOrg($this);
        }

        return $this;
    }

    public function removeMInout(MInout $mInout): self
    {
        if ($this->m_inouts->removeElement($mInout)) {
            // set the owning side to null (unless already changed)
            if ($mInout->getAdOrg() === $this) {
                $mInout->setAdOrg(null);
            }
        }

        return $this;
    }

}
