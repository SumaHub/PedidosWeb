<?php

namespace App\Entity;

use App\Repository\CDoctypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CDoctypeRepository::class)
 * @ORM\Table(name="C_DocType")
 */
class CDoctype
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $c_doctype_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_client_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_org_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isdefault;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $issotrx;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $docbasetype;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $docsubtypeso;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isdocnocontrolled;

    /**
     * @ORM\Column(type="integer")
     */
    private $docnosequence_id;

    public function getId(): ?int
    {
        return $this->getCDoctypeId();
    }

    public function getCDoctypeId(): ?int
    {
        return $this->c_doctype_id;
    }

    public function setCDoctypeId(int $c_doctype_id): self
    {
        $this->c_doctype_id = $c_doctype_id;

        return $this;
    }

    public function getAdClientId(): ?int
    {
        return $this->ad_client_id;
    }

    public function setAdClientId(int $ad_client_id): self
    {
        $this->ad_client_id = $ad_client_id;

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

    public function getIsdefault(): ?string
    {
        return $this->isdefault;
    }

    public function setIsdefault(string $isdefault): self
    {
        $this->isdefault = $isdefault;

        return $this;
    }

    public function getIssotrx(): ?string
    {
        return $this->issotrx;
    }

    public function setIssotrx(string $issotrx): self
    {
        $this->issotrx = $issotrx;

        return $this;
    }

    public function getDocbasetype(): ?string
    {
        return $this->docbasetype;
    }

    public function setDocbasetype(string $docbasetype): self
    {
        $this->docbasetype = $docbasetype;

        return $this;
    }

    public function getDocsubtypeso(): ?string
    {
        return $this->docsubtypeso;
    }

    public function setDocsubtypeso(string $docsubtypeso): self
    {
        $this->docsubtypeso = $docsubtypeso;

        return $this;
    }

    public function getIsdocnocontrolled(): ?string
    {
        return $this->isdocnocontrolled;
    }

    public function setIsdocnocontrolled(string $isdocnocontrolled): self
    {
        $this->isdocnocontrolled = $isdocnocontrolled;

        return $this;
    }

    public function getDocnosequenceId(): ?int
    {
        return $this->docnosequence_id;
    }

    public function setDocnosequenceId(int $docnosequence_id): self
    {
        $this->docnosequence_id = $docnosequence_id;

        return $this;
    }
}
