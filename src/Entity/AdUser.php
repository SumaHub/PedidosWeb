<?php

namespace App\Entity;

use App\Repository\AdUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdUserRepository::class)
 */
class AdUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $ad_user_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="integer")
     */
    private $createdby;
    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(type="integer")
     */
    private $updatedby;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $islocked;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateaccountlocked;

    /**
     * @ORM\Column(type="integer")
     */
    private $failedlogincount;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datepasswordchanged;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datelastlogin;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isexpired;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $securityquestion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $answer;

    /**
     * @ORM\ManyToOne(targetEntity=CBpartner::class)
     * @ORM\JoinColumn(referencedColumnName="c_bpartner_id", nullable=false)
     */
    private $c_bpartner;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_bpartner_id;

    public function getId(): int
    {
        return $this->getAdUserId();
    }

    public function getAdUserId(): ?int
    {
        return $this->ad_user_id;
    }

    public function setAdUserId(int $ad_user_id): self
    {
        $this->ad_user_id = $ad_user_id;

        return $this;
    }

    public function getIsactive(): ?bool
    {
        return ($this->isactive === 'Y');
    }

    public function setIsactive(string $isactive): self
    {
        $this->isactive = $isactive;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    public function getIslocked(): ?bool
    {
        return ($this->islocked === 'Y');
    }

    public function setIslocked(string $islocked): self
    {
        $this->islocked = $islocked;

        return $this;
    }

    public function getDateaccountlocked(): ?\DateTimeInterface
    {
        return $this->dateaccountlocked;
    }

    public function setDateaccountlocked(?\DateTimeInterface $dateaccountlocked): self
    {
        $this->dateaccountlocked = $dateaccountlocked;

        return $this;
    }

    public function getFailedlogincount(): ?int
    {
        return $this->failedlogincount;
    }

    public function setFailedlogincount(int $failedlogincount): self
    {
        $this->failedlogincount = $failedlogincount;

        return $this;
    }

    public function getDatepasswordchanged(): ?\DateTimeInterface
    {
        return $this->datepasswordchanged;
    }

    public function setDatepasswordchanged(?\DateTimeInterface $datepasswordchanged): self
    {
        $this->datepasswordchanged = $datepasswordchanged;

        return $this;
    }

    public function getDatelastlogin(): ?\DateTimeInterface
    {
        return $this->datelastlogin;
    }

    public function setDatelastlogin(?\DateTimeInterface $datelastlogin): self
    {
        $this->datelastlogin = $datelastlogin;

        return $this;
    }


    public function getIsexpired(): ?bool
    {
        return ($this->isexpired === 'Y');
    }

    public function setIsexpired(string $isexpired): self
    {
        $this->isexpired = $isexpired;

        return $this;
    }

    public function getSecurityquestion(): ?string
    {
        return $this->securityquestion;
    }

    public function setSecurityquestion(string $securityquestion): self
    {
        $this->securityquestion = $securityquestion;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

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
}
