<?php

namespace App\Entity\Main;

use App\Repository\Main\AdTabRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdTabRepository::class)
 */
class AdTab
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
    private $ad_tab_id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_table_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isinsertrecord;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isreadonly;

    /**
     * @ORM\Column(type="integer")
     */
    private $tablevel;

    /**
     * @ORM\Column(type="integer")
     */
    private $seqno;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdTabId(): ?int
    {
        return $this->ad_tab_id;
    }

    public function setAdTabId(int $ad_tab_id): self
    {
        $this->ad_tab_id = $ad_tab_id;

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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAdTableId(): ?int
    {
        return $this->ad_table_id;
    }

    public function setAdTableId(int $ad_table_id): self
    {
        $this->ad_table_id = $ad_table_id;

        return $this;
    }

    public function getIsinsertrecord(): ?string
    {
        return $this->isinsertrecord;
    }

    public function setIsinsertrecord(string $isinsertrecord): self
    {
        $this->isinsertrecord = $isinsertrecord;

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

    public function getIsreadonly(): ?string
    {
        return $this->isreadonly;
    }

    public function setIsreadonly(string $isreadonly): self
    {
        $this->isreadonly = $isreadonly;

        return $this;
    }

    public function getTablevel(): ?int
    {
        return $this->tablevel;
    }

    public function setTablevel(int $tablevel): self
    {
        $this->tablevel = $tablevel;

        return $this;
    }

    public function getSeqno(): ?int
    {
        return $this->seqno;
    }

    public function setSeqno(int $seqno): self
    {
        $this->seqno = $seqno;

        return $this;
    }
}
