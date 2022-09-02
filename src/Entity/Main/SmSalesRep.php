<?php

namespace App\Entity\Main;

use App\Repository\Main\SmSalesRepRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SmSalesRepRepository::class)
 */
class SmSalesRep
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $sm_sales_rep_id;

    /**
     * @ORM\ManyToOne(targetEntity=CBpartner::class, inversedBy="smSalesReps")
     * @ORM\JoinColumn(referencedColumnName="c_bpartner_id")
     */
    private $c_bpartner;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_bpartner_id;

    /**
     * @ORM\ManyToOne(targetEntity=AdUser::class)
     * @ORM\JoinColumn(name="salesrep_id", referencedColumnName="ad_user_id")
     */
    private $ad_user;

    /**
     * @ORM\Column(type="integer")
     */
    private $salesrep_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    public function getId(): ?int
    {
        return $this->getSmSalesRepId();
    }

    public function getSmSalesRepId(): ?int
    {
        return $this->sm_sales_rep_id;
    }

    public function setSmSalesRepId(int $sm_sales_rep_id): self
    {
        $this->sm_sales_rep_id = $sm_sales_rep_id;

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

    public function getAdUser(): ?AdUser
    {
        return $this->ad_user;
    }

    public function setAdUser(?AdUser $ad_user): self
    {
        $this->ad_user = $ad_user;

        return $this;
    }

    public function getSalesrepId(): ?int
    {
        return $this->salesrep_id;
    }

    public function setSalesrepId(int $salesrep_id): self
    {
        $this->salesrep_id = $salesrep_id;

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
}
