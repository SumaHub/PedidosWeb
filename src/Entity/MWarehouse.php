<?php

namespace App\Entity;

use App\Repository\MWarehouseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MWarehouseRepository::class)
 * @ORM\Table(name="m_warehouse")
 */
class MWarehouse
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $m_warehouse_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\ManyToOne(targetEntity=AdOrg::class, inversedBy="m_warehouse")
     * @ORM\JoinColumn(referencedColumnName="ad_org_id", nullable=false)
     */
    private $ad_org;

    public function getId(): int
    {
        return $this->getMWarehouseId();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getAdOrg(): ?AdOrg
    {
        return $this->ad_org;
    }

    public function setAdOrg(?AdOrg $ad_org): self
    {
        $this->ad_org = $ad_org;

        return $this;
    }
}
