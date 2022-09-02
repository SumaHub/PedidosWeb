<?php

namespace App\Entity\Main;

use App\Repository\Main\CBpartnerLocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CBpartnerLocationRepository::class)
 * @ORM\Table(name="c_bpartner_location")
 */
class CBpartnerLocation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $c_bpartner_location_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity=CBpartner::class, inversedBy="c_bpartner_location")
     * @ORM\JoinColumn(referencedColumnName="c_bpartner_id", nullable=false)
     */
    private $c_bpartner;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_bpartner_id;

    /**
     * @ORM\OneToOne(targetEntity=CLocation::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(referencedColumnName="c_location_id", nullable=false)
     */
    private $c_location;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_location_id;

    /**
     * @ORM\OneToMany(targetEntity=COrder::class, mappedBy="c_bpartner_location")
     */
    private $c_order;

    public function __construct()
    {
        $this->c_order = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->getCBpartnerLocationId();
    }

    public function getCBpartnerLocationId(): ?int
    {
        return $this->c_bpartner_location_id;
    }

    public function setCBpartnerLocationId(int $c_bpartner_location_id): self
    {
        $this->c_bpartner_location_id = $c_bpartner_location_id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCBpartner(): ?CBpartner
    {
        return $this->c_bpartner;
    }

    public function setCBpartner(?CBpartner $c_bpartner): self
    {
        $this->c_bpartner = $c_bpartner;

        return $this;
    }

    public function getCBpartnerId(): ?int
    {
        return $this->c_bpartner_id;
    }

    public function setCBpartnerId(int $c_bpartner_id): self
    {
        $this->c_bpartner_id = $c_bpartner_id;

        return $this;
    }

    public function getCLocation(): ?CLocation
    {
        return $this->c_location;
    }

    public function setCLocation(CLocation $c_location): self
    {
        $this->c_location = $c_location;

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

    /**
     * @return Collection|COrder[]
     */
    public function getCOrder(): Collection
    {
        return $this->c_order;
    }
}
