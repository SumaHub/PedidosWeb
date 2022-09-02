<?php

namespace App\Entity\Main;

use App\Repository\Main\AdProcessAccessRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdProcessAccessRepository::class)
 */
class AdProcessAccess
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=36)
     */
    private $ad_process_access_uu;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_role_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_process_id;

    public function getId(): ?string
    {
        return $this->getAdProcessAccessUu();
    }

    public function getAdProcessAccessUu(): ?string
    {
        return $this->ad_process_access_uu;
    }

    public function setAdProcessAccessUu(string $ad_process_access_uu): self
    {
        $this->ad_process_access_uu = $ad_process_access_uu;

        return $this;
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

    public function getAdProcessId(): ?int
    {
        return $this->ad_process_id;
    }

    public function setAdProcessId(int $ad_process_id): self
    {
        $this->ad_process_id = $ad_process_id;

        return $this;
    }
}
