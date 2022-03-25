<?php

namespace App\Entity;

use App\Repository\MProductCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MProductCategoryRepository::class)
 * @ORM\Table(name="m_product_category")
 */
class MProductCategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $m_product_category_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\OneToMany(targetEntity=MProduct::class, mappedBy="m_product_category")
     */
    private $m_product;

    public function __construct()
    {
        $this->m_product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $mProduct->setMProductCategory($this);
        }

        return $this;
    }

    public function removeMProduct(MProduct $mProduct): self
    {
        if ($this->m_product->removeElement($mProduct)) {
            // set the owning side to null (unless already changed)
            if ($mProduct->getMProductCategory() === $this) {
                $mProduct->setMProductCategory(null);
            }
        }

        return $this;
    }
}
