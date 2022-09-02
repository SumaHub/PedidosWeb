<?php

namespace App\Entity\Main;

use App\Repository\Main\MProductdownloadRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MProductdownloadRepository::class)
 * @ORM\Table(name="M_ProductDownload")
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
     * @ORM\ManyToOne(targetEntity=MProduct::class, inversedBy="m_productdownload")
     * @ORM\JoinColumn(referencedColumnName="m_product_id")
     */
    private $m_product;

    /**
     * @ORM\Column(type="integer")
     */
    private $m_product_id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $downloadurl;

    /**
     * @ORM\Column(type="string", length=36)
     */
    private $m_productdownload_uu;

    /**
     * @ORM\Column(type="string", length=1)
     */
    // private $iscover;

    /**
     * @ORM\Column(type="integer")
     */
    // private $seqno;

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

    public function getMProductdownloadUu(): ?string
    {
        return $this->m_productdownload_uu;
    }

    public function setMProductdownloadUu(string $m_productdownload_uu): self
    {
        $this->m_productdownload_uu = $m_productdownload_uu;

        return $this;
    }

    // TODO: Crear campos en iDempiere
    // public function getIscover(): ?string
    // {
    //     return $this->iscover;
    // }

    // public function setIscover(string $iscover): self
    // {
    //     $this->iscover = $iscover;

    //     return $this;
    // }

    // public function getSeqno(): ?int
    // {
    //     return $this->seqno;
    // }

    // public function setSeqno(int $seqno): self
    // {
    //     $this->seqno = $seqno;

    //     return $this;
    // }
}
