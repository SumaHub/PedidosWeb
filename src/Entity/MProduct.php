<?php

namespace App\Entity;

use App\Repository\MProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MProductRepository::class)
 * @ORM\Table(name="m_product")
 */
class MProduct
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $m_product_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=SmMarca::class, inversedBy="m_product")
     * @ORM\JoinColumn(referencedColumnName="sm_marca_id", nullable=false)
     */
    private $sm_marca;

    /**
     * @ORM\Column(type="integer")
     */
    private $sm_marca_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=MProductCategory::class, inversedBy="m_product")
     * @ORM\JoinColumn(referencedColumnName="m_product_category_id", nullable=false)
     */
    private $m_product_category;

    /**
     * @ORM\Column(type="integer")
     */
    private $m_product_category_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $issold;

    /**
     * @ORM\OneToMany(targetEntity=MProductdownload::class, mappedBy="m_product")
     */
    private $m_productdownload;

    /**
     * @ORM\ManyToOne(targetEntity=SmModeloProducto::class)
     * @ORM\JoinColumn(referencedColumnName="sm_modelo_producto_id", nullable=false)
     */
    private $sm_modelo_producto;

    /**
     * @ORM\Column(type="integer")
     */
    private $sm_modelo_producto_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity=AdUser::class)
     * @ORM\JoinColumn(name="createdby", referencedColumnName="ad_user_id", nullable=false)
     */
    private $ad_user;

    /**
     * @ORM\Column(type="integer")
     */
    private $createdby;

    /**
     * @ORM\OneToMany(targetEntity=MProductprice::class, mappedBy="m_product")
     */
    private $m_productprices;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_taxcategory_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_uom_id;

    public function __construct()
    {
        $this->m_productdownload = new ArrayCollection();
        $this->m_productprices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->getMProductId();
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

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

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

    public function getSmMarca(): ?SmMarca
    {
        return $this->sm_marca;
    }

    public function setSmMarca(?SmMarca $sm_marca): self
    {
        $this->sm_marca = $sm_marca;

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

    public function getMProductCategory(): ?MProductCategory
    {
        return $this->m_product_category;
    }

    public function setMProductCategory(?MProductCategory $m_product_category): self
    {
        $this->m_product_category = $m_product_category;

        return $this;
    }

    public function getMProductCategoryId(): ?int
    {
        return $this->m_product_category_id;
    }

    public function setMProductCategoryId(int $m_product_category_id): self
    {
        $this->m_product_category_id = $m_product_category_id;

        return $this;
    }

    public function getIsactive(): ?string
    {
        return ($this->isactive === 'Y');
    }

    public function setIsactive(string $isactive): self
    {
        $this->isactive = $isactive;

        return $this;
    }

    public function getIssold(): ?string
    {
        return $this->issold;
    }

    public function setIssold(string $issold): self
    {
        $this->issold = $issold;

        return $this;
    }

    /**
     * @return Collection|MProductdownload[]
     */
    public function getMProductdownload(): Collection
    {
        return $this->m_productdownload;
    }

    public function addMProductdownload(MProductdownload $mProductdownload): self
    {
        if (!$this->m_productdownload->contains($mProductdownload)) {
            $this->m_productdownload[] = $mProductdownload;
            $mProductdownload->setMProduct($this);
        }

        return $this;
    }

    public function removeMProductdownload(MProductdownload $mProductdownload): self
    {
        if ($this->m_productdownload->removeElement($mProductdownload)) {
            // set the owning side to null (unless already changed)
            if ($mProductdownload->getMProduct() === $this) {
                $mProductdownload->setMProduct(null);
            }
        }

        return $this;
    }

    public function getSmModeloProducto(): ?SmModeloProducto
    {
        return $this->sm_modelo_producto;
    }

    public function setSmModeloProducto(?SmModeloProducto $sm_modelo_producto): self
    {
        $this->sm_modelo_producto = $sm_modelo_producto;

        return $this;
    }

    public function getSmModeloProductoId(): ?int
    {
        return $this->sm_modelo_producto_id;
    }

    public function setSmModeloProductoId(int $sm_modelo_producto_id): self
    {
        $this->sm_modelo_producto_id = $sm_modelo_producto_id;

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

    public function getAdUser(): ?AdUser
    {
        return $this->ad_user;
    }

    public function setAdUser(?AdUser $ad_user): self
    {
        $this->ad_user = $ad_user;

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
            $mProductprice->setMProduct($this);
        }

        return $this;
    }

    public function removeMProductprice(MProductprice $mProductprice): self
    {
        if ($this->m_productprices->removeElement($mProductprice)) {
            // set the owning side to null (unless already changed)
            if ($mProductprice->getMProduct() === $this) {
                $mProductprice->setMProduct(null);
            }
        }

        return $this;
    }

    public function getCTaxcategoryId(): ?int
    {
        return $this->c_taxcategory_id;
    }

    public function setCTaxcategoryId(int $c_taxcategory_id): self
    {
        $this->c_taxcategory_id = $c_taxcategory_id;

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
}
