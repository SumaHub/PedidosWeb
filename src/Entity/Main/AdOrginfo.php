<?php

namespace App\Entity\Main;

use App\Repository\Main\AdOrginfoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdOrginfoRepository::class)
 * @ORM\Table(name="ad_orginfo")
 */
class AdOrginfo
{
    /**
     * @ORM\ManyToOne(targetEntity=AdOrg::class, inversedBy="ad_orginfo")
     * @ORM\JoinColumn(referencedColumnName="ad_org_id", nullable=false)
     */
    private $ad_org;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $ad_org_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $sm_marca_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $taxid;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_location_id;

    public function getAdOrg(): ?AdOrg
    {
        return $this->ad_org;
    }

    public function setAdOrg(?AdOrg $ad_org): self
    {
        $this->ad_org = $ad_org;

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

    public function getSmMarcaId(): ?int
    {
        return $this->sm_marca_id;
    }

    public function setSmMarcaId(int $sm_marca_id): self
    {
        $this->sm_marca_id = $sm_marca_id;

        return $this;
    }

    public function getTaxid(): ?string
    {
        return $this->taxid;
    }

    public function setTaxid(string $taxid): self
    {
        $this->taxid = $taxid;

        return $this;
    }

    public function getCLocationId(): ?int
    {
        return $this->c_location_id;
    }

    public function setCLocationId(int $c_location_id): self
    {
        $this->c_location_id = $c_location_id;

        return $this;
    }
}
