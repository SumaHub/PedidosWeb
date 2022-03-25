<?php

namespace App\Entity;

use App\Repository\AdClientRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdClientRepository::class)
 * @ORM\Table(name="ad_client")
 */
class AdClient
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $ad_client_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;
    
    public function getId(): ?int
    {
        return $this->getAdClientId();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
