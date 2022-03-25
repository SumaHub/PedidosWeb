<?php

namespace App\Entity;

use App\Repository\MProductdownloadRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MProductdownloadRepository::class)
 * @ORM\Table(name="m_productdownload")
 */
class MProductdownload
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $m_productdownload_id;

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
     * @ORM\ManyToOne(targetEntity=MProduct::class, inversedBy="m_productdownload")
     * @ORM\JoinColumn(referencedColumnName="m_product_id", nullable=false)
     */
    private $m_product;

    /**
     * @ORM\Column(type="integer")
     */
    private $m_product_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $downloadurl;

    public function getId(): ?int
    {
        return $this->getMProductdownloadId();
    }

    public function getMProductdownloadId(): ?int
    {
        return $this->m_productdownload_id;
    }

    public function setMProductdownloadId(int $m_productdownload_id): self
    {
        $this->m_productdownload_id = $m_productdownload_id;

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

    public function setMProductId(int $m_product_id): self
    {
        $this->m_product_id = $m_product_id;

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

    public function getDownloadurl(): ?string
    {
        return $this->downloadurl;
    }

    public function setDownloadurl(string $downloadurl): self
    {
        $this->downloadurl = $downloadurl;

        return $this;
    }
}