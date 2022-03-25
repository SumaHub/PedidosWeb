<?php

namespace App\Entity;

use App\Repository\SmModeloProductoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SmModeloProductoRepository::class)
 * @ORM\Table(name="sm_modelo_producto")
 */
class SmModeloProducto
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $sm_modelo_producto_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
