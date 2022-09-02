<?php

namespace App\Entity\Main;

use App\Repository\Main\SmPrecioEstimadolineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SmPrecioEstimadolineRepository::class)
 * @ORM\Table(name="sm_precio_estimadoline")
 */
class SmPrecioEstimadoline
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $sm_precio_estimadoline_id;

    /**
     * @ORM\ManyToOne(targetEntity=SmPrecioEstimado::class, inversedBy="sm_precio_estimadolines")
     * @ORM\JoinColumn(referencedColumnName="sm_precio_estimado_id", nullable=false)
     */
    private $sm_precio_estimado;

    /**
     * @ORM\Column(type="integer")
     */
    private $sm_precio_estimado_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $m_product_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $listo;

    /**
     * @ORM\Column(type="float")
     */
    private $pricestd;

    /**
     * @ORM\ManyToOne(targetEntity=MPricelistVersion::class, inversedBy="sm_precio_estimadolines")
     * @ORM\JoinColumn(referencedColumnName="m_pricelist_version_id")
     */
    private $m_pricelist_version;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $m_pricelist_version_id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $fletexpieza;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $impuestoxpieza;

    public function getId(): ?int
    {
        return $this->getSmPrecioEstimadolineId();
    }

    public function getSmPrecioEstimadolineId(): ?int
    {
        return $this->sm_precio_estimadoline_id;
    }

    public function setSmPrecioEstimadolineId(int $sm_precio_estimadoline_id): self
    {
        $this->sm_precio_estimadoline_id = $sm_precio_estimadoline_id;

        return $this;
    }

    public function getSmPrecioEstimado(): ?SmPrecioEstimado
    {
        return $this->sm_precio_estimado;
    }

    public function setSmPrecioEstimado(?SmPrecioEstimado $sm_precio_estimado): self
    {
        $this->sm_precio_estimado = $sm_precio_estimado;

        return $this;
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

    public function getMProductId(): ?int
    {
        return $this->m_product_id;
    }

    public function setMProductId(int $m_product_id): self
    {
        $this->m_product_id = $m_product_id;

        return $this;
    }

    public function getListo(): ?string
    {
        return $this->listo;
    }

    public function setListo(string $listo): self
    {
        $this->listo = $listo;

        return $this;
    }

    public function getPricestd(): ?float
    {
        return $this->pricestd;
    }

    public function setPricestd(float $pricestd): self
    {
        $this->pricestd = $pricestd;

        return $this;
    }

    public function getMPricelistVersion(): ?MPricelistVersion
    {
        return $this->m_pricelist_version;
    }

    public function setMPricelistVersion(?MPricelistVersion $m_pricelist_version): self
    {
        $this->m_pricelist_version = $m_pricelist_version;

        return $this;
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

    public function getFletexpieza(): ?float
    {
        return $this->fletexpieza;
    }

    public function setFletexpieza(?float $fletexpieza): self
    {
        $this->fletexpieza = $fletexpieza;

        return $this;
    }

    public function getImpuestoxpieza(): ?float
    {
        return $this->impuestoxpieza;
    }

    public function setImpuestoxpieza(?float $impuestoxpieza): self
    {
        $this->impuestoxpieza = $impuestoxpieza;

        return $this;
    }
}
