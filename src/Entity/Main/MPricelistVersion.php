<?php

namespace App\Entity\Main;

use App\Repository\Main\MPricelistVersionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MPricelistVersionRepository::class)
 * @ORM\Table(name="m_pricelist_version")
 */
class MPricelistVersion
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $m_pricelist_version_id;

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
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=MPricelist::class, inversedBy="m_pricelist_versions")
     * @ORM\JoinColumn(referencedColumnName="m_pricelist_id", nullable=false)
     */
    private $m_pricelist;

    /**
     * @ORM\Column(type="integer")
     */
    private $m_pricelist_id;

    /**
     * @ORM\OneToMany(targetEntity=MProductprice::class, mappedBy="m_pricelist_version")
     */
    private $m_productprices;

    /**
     * @ORM\OneToMany(targetEntity=SmPrecioEstimadoline::class, mappedBy="m_pricelist_version")
     */
    private $sm_precio_estimadolines;

    public function __construct()
    {
        $this->m_productprices = new ArrayCollection();
        $this->sm_precio_estimadolines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->getMPricelistVersionId();
    }

    public function getMPricelistVersionId(): ?int
    {
        return $this->m_pricelist_version_id;
    }

    public function setMPricelistVersionId(int $m_pricelist_version_id): self
    {
        $this->m_pricelist_version_id = $m_pricelist_version_id;

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

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    /**
     * @return Collection|MProductprice[]
     */
    public function getMProductprices(): Collection
    {
        return $this->m_productprices;
    }

    public function addMProductprice(MProductprice $mProductprice): self
    {
        if (!$this->m_productprices->contains($mProductprice)) {
            $this->m_productprices[] = $mProductprice;
            $mProductprice->setMPricelistVersion($this);
        }

        return $this;
    }

    public function removeMProductprice(MProductprice $mProductprice): self
    {
        if ($this->m_productprices->removeElement($mProductprice)) {
            // set the owning side to null (unless already changed)
            if ($mProductprice->getMPricelistVersion() === $this) {
                $mProductprice->setMPricelistVersion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SmPrecioEstimadoline[]
     */
    public function getSmPrecioEstimadolines(): Collection
    {
        return $this->sm_precio_estimadolines;
    }
}
