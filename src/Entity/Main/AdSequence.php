<?php

namespace App\Entity\Main;

use App\Repository\Main\AdSequenceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdSequenceRepository::class)
 * @ORM\Table(name="ad_sequence")
 */
class AdSequence
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $ad_sequence_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_org_id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $currentnext;

    /**
     * @ORM\Column(type="integer")
     */
    private $incrementno;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isorglevelsequence;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $prefix;

    public function getId(): ?int
    {
        return $this->getAdSequenceId();
    }

    public function getAdSequenceId(): ?int
    {
        return $this->ad_sequence_id;
    }

    public function setAdSequenceId(int $ad_sequence_id): self
    {
        $this->ad_sequence_id = $ad_sequence_id;

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

    public function getCurrentnext(): ?int
    {
        return $this->currentnext;
    }

    public function setCurrentnext(int $currentnext): self
    {
        $this->currentnext = $currentnext;

        return $this;
    }

    public function getIncrementno(): ?int
    {
        return $this->incrementno;
    }

    public function setIncrementno(int $incrementno): self
    {
        $this->incrementno = $incrementno;

        return $this;
    }

    public function getIsorglevelsequence(): ?string
    {
        return ( $this->isorglevelsequence === 'Y' );
    }

    public function setIsorglevelsequence(string $isorglevelsequence): self
    {
        $this->isorglevelsequence = $isorglevelsequence;

        return $this;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }
}
