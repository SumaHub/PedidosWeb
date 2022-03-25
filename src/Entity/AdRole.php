<?php

namespace App\Entity;

use App\Repository\AdRoleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdRoleRepository::class)
 */
class AdRole
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
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

    public function getId(): ?int
    {
        return $this->id;
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
}
