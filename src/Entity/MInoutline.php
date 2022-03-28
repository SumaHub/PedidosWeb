<?php

namespace App\Entity;

use App\Repository\MInoutlineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MInoutlineRepository::class)
 * @ORM\Table(name="m_inoutline")
 */
class MInoutline
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $m_inoutline_id;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=MInout::class, inversedBy="m_inoutlines")
     * @ORM\JoinColumn(referencedColumnName="m_inout_id")
     */
    private $m_inout;

    /**
     * @ORM\Column(type="integer")
     */
    private $m_inout_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $c_orderline_id;

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
     * @ORM\Column(type="integer")
     */
    private $c_uom_id;

    /**
     * @ORM\Column(type="float")
     */
    private $movementqty;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isinvoiced;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isdescription;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $confirmedqty;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pickedqty;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $processed;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qtyentered;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $c_charge_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $m_rmaline_id;

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    private $m_inoutline_uu;

    public function getId(): ?int
    {
        return $this->getMInoutlineId();
    }

    public function getMInoutlineId(): ?int
    {
        return $this->m_inoutline_id;
    }

    public function setMInoutlineId(int $m_inoutline_id): self
    {
        $this->m_inoutline_id = $m_inoutline_id;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMInout(): ?MInout
    {
        return $this->m_inout;
    }

    public function setMInout(?MInout $m_inout): self
    {
        $this->m_inout = $m_inout;

        return $this;
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

    public function getCOrderlineId(): ?int
    {
        return $this->c_orderline_id;
    }

    public function setCOrderlineId(?int $c_orderline_id): self
    {
        $this->c_orderline_id = $c_orderline_id;

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

    public function getCUomId(): ?int
    {
        return $this->c_uom_id;
    }

    public function setCUomId(int $c_uom_id): self
    {
        $this->c_uom_id = $c_uom_id;

        return $this;
    }

    public function getMovementqty(): ?float
    {
        return $this->movementqty;
    }

    public function setMovementqty(float $movementqty): self
    {
        $this->movementqty = $movementqty;

        return $this;
    }

    public function getIsinvoiced(): ?string
    {
        return $this->isinvoiced;
    }

    public function setIsinvoiced(string $isinvoiced): self
    {
        $this->isinvoiced = $isinvoiced;

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

    public function getConfirmedqty(): ?float
    {
        return $this->confirmedqty;
    }

    public function setConfirmedqty(?float $confirmedqty): self
    {
        $this->confirmedqty = $confirmedqty;

        return $this;
    }

    public function getPickedqty(): ?float
    {
        return $this->pickedqty;
    }

    public function setPickedqty(?float $pickedqty): self
    {
        $this->pickedqty = $pickedqty;

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

    public function getCChargeId(): ?int
    {
        return $this->c_charge_id;
    }

    public function setCChargeId(?int $c_charge_id): self
    {
        $this->c_charge_id = $c_charge_id;

        return $this;
    }

    public function getMRmalineId(): ?int
    {
        return $this->m_rmaline_id;
    }

    public function setMRmalineId(?int $m_rmaline_id): self
    {
        $this->m_rmaline_id = $m_rmaline_id;

        return $this;
    }

    public function getMInoutlineUu(): ?string
    {
        return $this->m_inoutline_uu;
    }

    public function setMInoutlineUu(?string $m_inoutline_uu): self
    {
        $this->m_inoutline_uu = $m_inoutline_uu;

        return $this;
    }
}
