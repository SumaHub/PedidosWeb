<?php

namespace App\Entity\Main;

use App\Repository\Main\SmPrecioEstimadoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SmPrecioEstimadoRepository::class)
 * @ORM\Table(name="sm_precio_estimado")
 */
class SmPrecioEstimado
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $sm_precio_estimado_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive2;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $docstatus;

    public static $DOCSTATUS_DRAFT = "N";

    public static $DOCSTATUS_FORPROMOTION = "P";

    public static $DOCSTATUS_PROCESSED = "Y";

    public static $DOCSTATUS_VOIDED = "VO";

    /**
     * @ORM\OneToMany(targetEntity=SmPrecioEstimadoline::class, mappedBy="sm_precio_estimado")
     */
    private $sm_precio_estimadolines;

    /**
     * @ORM\Column(type="float")
     */
    private $fletexcontenedor;

    /**
     * @ORM\Column(type="float")
     */
    private $nacionalizacionxcontenedor;

    /**
     * @ORM\Column(type="float")
     */
    private $utilidaddeseada;

    public function __construct()
    {
        $this->sm_precio_estimadolines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->getSmPrecioEstimadoId();
    }

    public function getSmPrecioEstimadoId(): ?int
    {
        return $this->sm_precio_estimado_id;
    }

    public function setSmPrecioEstimadoId(int $sm_precio_estimado_id): self
    {
        $this->sm_precio_estimado_id = $sm_precio_estimado_id;

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

    public function getIsactive2(): ?string
    {
        return $this->isactive2;
    }

    public function setIsactive2(string $isactive2): self
    {
        $this->isactive2 = $isactive2;

        return $this;
    }

    public function getDocstatus(): ?string
    {
        return $this->docstatus;
    }

    public function setDocstatus(string $docstatus): self
    {
        $this->docstatus = $docstatus;

        return $this;
    }

    /**
     * @return Collection|SmPrecioEstimadoline[]
     */
    public function getSmPrecioEstimadolines(): Collection
    {
        return $this->sm_precio_estimadolines;
    }

    public function addSmPrecioEstimadoline(SmPrecioEstimadoline $smPrecioEstimadoline): self
    {
        if (!$this->sm_precio_estimadolines->contains($smPrecioEstimadoline)) {
            $this->sm_precio_estimadolines[] = $smPrecioEstimadoline;
            $smPrecioEstimadoline->setSmPrecioEstimado($this);
        }

        return $this;
    }

    public function removeSmPrecioEstimadoline(SmPrecioEstimadoline $smPrecioEstimadoline): self
    {
        if ($this->sm_precio_estimadolines->removeElement($smPrecioEstimadoline)) {
            // set the owning side to null (unless already changed)
            if ($smPrecioEstimadoline->getSmPrecioEstimado() === $this) {
                $smPrecioEstimadoline->setSmPrecioEstimado(null);
            }
        }

        return $this;
    }

    public function getFletexcontenedor(): ?float
    {
        return $this->fletexcontenedor;
    }

    public function setFletexcontenedor(float $fletexcontenedor): self
    {
        $this->fletexcontenedor = $fletexcontenedor;

        return $this;
    }

    public function getNacionalizacionxcontenedor(): ?float
    {
        return $this->nacionalizacionxcontenedor;
    }

    public function setNacionalizacionxcontenedor(float $nacionalizacionxcontenedor): self
    {
        $this->nacionalizacionxcontenedor = $nacionalizacionxcontenedor;

        return $this;
    }

    public function getUtilidaddeseada(): ?float
    {
        return $this->utilidaddeseada;
    }

    public function setUtilidaddeseada(float $utilidaddeseada): self
    {
        $this->utilidaddeseada = $utilidaddeseada;

        return $this;
    }
}
