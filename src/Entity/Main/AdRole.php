<?php

namespace App\Entity\Main;

use App\Repository\Main\AdRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdRoleRepository::class)
 */
class AdRole
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $ad_role_id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $ismasterrole;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isuseuserorgaccess;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $iscanexport;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $ischangelog;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isshowacct;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $c_currency_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $iscanapproveowndoc;

    /**
     * @ORM\OneToMany(targetEntity=AdRoleIncluded::class, mappedBy="ad_role")
     */
    private $adRoleIncludeds;

    public function __construct()
    {
        $this->adRoleIncludeds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->getAdRoleId();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsmasterrole(): ?string
    {
        return $this->ismasterrole;
    }

    public function setIsmasterrole(string $ismasterrole): self
    {
        $this->ismasterrole = $ismasterrole;

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

    public function getIsuseuserorgaccess(): ?string
    {
        return $this->isuseuserorgaccess;
    }

    public function setIsuseuserorgaccess(string $isuseuserorgaccess): self
    {
        $this->isuseuserorgaccess = $isuseuserorgaccess;

        return $this;
    }

    public function getIscanexport(): ?string
    {
        return $this->iscanexport;
    }

    public function setIscanexport(string $iscanexport): self
    {
        $this->iscanexport = $iscanexport;

        return $this;
    }

    public function getIschangelog(): ?string
    {
        return $this->ischangelog;
    }

    public function setIschangelog(string $ischangelog): self
    {
        $this->ischangelog = $ischangelog;

        return $this;
    }

    public function getIsshowacct(): ?string
    {
        return $this->isshowacct;
    }

    public function setIsshowacct(string $isshowacct): self
    {
        $this->isshowacct = $isshowacct;

        return $this;
    }

    public function getCCurrencyId(): ?int
    {
        return $this->c_currency_id;
    }

    public function setCCurrencyId(?int $c_currency_id): self
    {
        $this->c_currency_id = $c_currency_id;

        return $this;
    }

    public function getIscanapproveowndoc(): ?string
    {
        return $this->iscanapproveowndoc;
    }

    public function setIscanapproveowndoc(string $iscanapproveowndoc): self
    {
        $this->iscanapproveowndoc = $iscanapproveowndoc;

        return $this;
    }

    /**
     * @return Collection|AdRoleIncluded[]
     */
    public function getAdRoleIncludeds(): Collection
    {
        return $this->adRoleIncludeds;
    }

    public function addAdRoleIncluded(AdRoleIncluded $adRoleIncluded): self
    {
        if (!$this->adRoleIncludeds->contains($adRoleIncluded)) {
            $this->adRoleIncludeds[] = $adRoleIncluded;
            $adRoleIncluded->setAdRole($this);
        }

        return $this;
    }

    public function removeAdRoleIncluded(AdRoleIncluded $adRoleIncluded): self
    {
        if ($this->adRoleIncludeds->removeElement($adRoleIncluded)) {
            // set the owning side to null (unless already changed)
            if ($adRoleIncluded->getAdRole() === $this) {
                $adRoleIncluded->setAdRole(null);
            }
        }

        return $this;
    }
}
