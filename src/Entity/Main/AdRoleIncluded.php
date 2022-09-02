<?php

namespace App\Entity\Main;

use App\Repository\Main\AdRoleIncludedRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdRoleIncludedRepository::class)
 */
class AdRoleIncluded
{
    /**
     * @ORM\Column(type="integer")
     */
    private $ad_role_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $included_role_id;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=36)
     */
    private $ad_role_included_uu;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\ManyToOne(targetEntity=AdRole::class, inversedBy="adRoleIncludeds")
     * @ORM\JoinColumn(referencedColumnName="ad_role_id")
     */
    private $ad_role;

    public function getId(): ?string
    {
        return $this->getAdRoleIncludedUu();
    }

    public function getAdRoleId(): ?int
    {
        return $this->ad_role_id;
    }

    public function setAdRoleId(int $ad_role_id): self
    {
        $this->ad_role_id = $ad_role_id;

        return $this;
    }

    public function getIncludedRoleId(): ?int
    {
        return $this->included_role_id;
    }

    public function setIncludedRoleId(int $included_role_id): self
    {
        $this->included_role_id = $included_role_id;

        return $this;
    }

    public function getAdRoleIncludedUu(): ?string
    {
        return $this->ad_role_included_uu;
    }

    public function setAdRoleIncludedUu(string $ad_role_included_uu): self
    {
        $this->ad_role_included_uu = $ad_role_included_uu;

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

    public function getAdRole(): ?AdRole
    {
        return $this->ad_role;
    }

    public function setAdRole(?AdRole $ad_role): self
    {
        $this->ad_role = $ad_role;

        return $this;
    }
}
