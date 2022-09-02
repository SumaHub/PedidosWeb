<?php

namespace App\Entity\Main;

use App\Repository\Main\SmCategoriaProductoMarcaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SmCategoriaProductoMarcaRepository::class)
 */
class SmCategoriaProductoMarca
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $sm_categoria_producto_marca_id;

    /**
     * @ORM\ManyToOne(targetEntity=SmMarca::class, inversedBy="sm_categoria_producto_marca")
     * @ORM\JoinColumn(referencedColumnName="sm_marca_id", nullable=false)
     */
    private $smMarca;

    /**
     * @ORM\Column(type="integer")
     */
    private $sm_marca_id;

    /**
     * @ORM\ManyToOne(targetEntity=MProductCategory::class)
     * @ORM\JoinColumn(referencedColumnName="m_product_category_id", nullable=false)
     */
    private $m_product_category;

    /**
     * @ORM\Column(type="integer")
     */
    private $m_product_category_id;

    public function getId(): ?int
    {
        return $this->getSmCategoriaProductoMarcaId();
    }

    public function getSmCategoriaProductoMarcaId(): ?int
    {
        return $this->sm_categoria_producto_marca_id;
    }

    public function setSmCategoriaProductoMarcaId(int $sm_categoria_producto_marca_id): self
    {
        $this->sm_categoria_producto_marca_id = $sm_categoria_producto_marca_id;

        return $this;
    }

    public function getSmMarca(): ?SmMarca
    {
        return $this->smMarca;
    }

    public function setSmMarca(?SmMarca $smMarca): self
    {
        $this->smMarca = $smMarca;

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
}
