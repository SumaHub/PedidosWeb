<?php

namespace App\Entity\Main;

use App\Repository\Main\SmMarcaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SmMarcaRepository::class)
 */
class SmMarca
{
    /**
     * @ORM\Id/
     * @ORM\Column(type="integer")
     */
    private $sm_marca_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=MProduct::class, mappedBy="sm_marca")
     */
    private $m_product;

    /**
     * @ORM\OneToMany(targetEntity=SmCategoriaProductoMarca::class, mappedBy="smMarca")
     */
    private $sm_categoria_producto_marca;

    public function __construct()
    {
        $this->m_product = new ArrayCollection();
        $this->sm_categoria_producto_marca = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|MProduct[]
     */
    public function getMProduct(): Collection
    {
        return $this->m_product;
    }

    public function addMProduct(MProduct $mProduct): self
    {
        if (!$this->m_product->contains($mProduct)) {
            $this->m_product[] = $mProduct;
            $mProduct->setSmMarca($this);
        }

        return $this;
    }

    public function removeMProduct(MProduct $mProduct): self
    {
        if ($this->m_product->removeElement($mProduct)) {
            // set the owning side to null (unless already changed)
            if ($mProduct->getSmMarca() === $this) {
                $mProduct->setSmMarca(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SmCategoriaProductoMarca[]
     */
    public function getSmCategoriaProductoMarca(): Collection
    {
        return $this->sm_categoria_producto_marca;
    }

    public function addSmCategoriaProductoMarca(SmCategoriaProductoMarca $smCategoriaProductoMarca): self
    {
        if (!$this->sm_categoria_producto_marca->contains($smCategoriaProductoMarca)) {
            $this->sm_categoria_producto_marca[] = $smCategoriaProductoMarca;
            $smCategoriaProductoMarca->setSmMarca($this);
        }

        return $this;
    }

    public function removeSmCategoriaProductoMarca(SmCategoriaProductoMarca $smCategoriaProductoMarca): self
    {
        if ($this->sm_categoria_producto_marca->removeElement($smCategoriaProductoMarca)) {
            // set the owning side to null (unless already changed)
            if ($smCategoriaProductoMarca->getSmMarca() === $this) {
                $smCategoriaProductoMarca->setSmMarca(null);
            }
        }

        return $this;
    }
}
