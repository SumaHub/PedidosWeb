<?php

namespace App\Entity\Main;

use App\Repository\Main\CPaymenttermRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CPaymenttermRepository::class)
 * @ORM\Table(name="c_paymentterm")
 */
class CPaymentterm
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $c_paymentterm_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $c_paymentterm_uu;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isdefault;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $paymenttermusage;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_client_id;

    public function getId(): ?int
    {
        return $this->getCPaymenttermId();
    }

    public function getCPaymenttermId(): ?int
    {
        return $this->c_paymentterm_id;
    }

    public function setCPaymenttermId(int $c_paymentterm_id): self
    {
        $this->c_paymentterm_id = $c_paymentterm_id;

        return $this;
    }

    public function getCPaymenttermUu(): ?string
    {
        return $this->c_paymentterm_uu;
    }

    public function setMPaymenttermUu(string $m_paymentterm_uu): self
    {
        $this->m_paymentterm_uu = $m_paymentterm_uu;

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

    public function getIsdefault(): ?string
    {
        return $this->isdefault;
    }

    public function setIsdefault(string $isdefault): self
    {
        $this->isdefault = $isdefault;

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

    public function getPaymenttermusage(): ?string
    {
        return $this->paymenttermusage;
    }

    public function setPaymenttermusage(string $paymenttermusage): self
    {
        $this->paymenttermusage = $paymenttermusage;

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
}
