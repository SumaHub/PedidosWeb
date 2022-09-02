<?php

namespace App\Entity\Main;

use App\Repository\Main\SmCharacteristicRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SmCharacteristicRepository::class)
 * @ORM\Table(name="sm_characteristic")
 */
class SmCharacteristic
{
    /**
     * @ORM\Column(type="integer")
     */
    private $ad_client_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_org_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="integer")
     */
    private $createdby;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $downloadurl;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $name;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $sm_characteristic_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sm_characteristic_uu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(type="integer")
     */
    private $updatedby;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->getSmCharacteristicId();
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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getCreatedby(): ?int
    {
        return $this->createdby;
    }

    public function setCreatedby(int $createdby): self
    {
        $this->createdby = $createdby;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDownloadurl(): ?string
    {
        return $this->downloadurl;
    }

    public function setDownloadurl(string $downloadurl): self
    {
        $this->downloadurl = $downloadurl;

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

    public function getSmCharacteristicId(): ?int
    {
        return $this->sm_characteristic_id;
    }

    public function setSmCharacteristicId(int $sm_characteristic_id): self
    {
        $this->sm_characteristic_id = $sm_characteristic_id;

        return $this;
    }

    public function getCmCharacteristicUu(): ?string
    {
        return $this->sm_characteristic_uu;
    }

    public function setCmCharacteristicUu(string $sm_characteristic_uu): self
    {
        $this->sm_characteristic_uu = $sm_characteristic_uu;

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

    public function getUpdatedby(): ?int
    {
        return $this->updatedby;
    }

    public function setUpdatedby(int $updatedby): self
    {
        $this->updatedby = $updatedby;

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
}
