<?php

namespace App\Entity\Main;

use App\Repository\Main\AdWindowAccessRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdWindowAccessRepository::class)
 */
class AdWindowAccess
{
    /**
     * @ORM\Column(type="integer")
     */
    private $ad_role_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ad_window_id;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=36)
     */
    private $ad_window_access_uu;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isactive;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $isreadwrite;

    public function getId(): ?int
    {
        return $this->getAdWindowAccessUu();
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

    public function getAdWindowId(): ?int
    {
        return $this->ad_window_id;
    }

    public function setAdWindowId(int $ad_window_id): self
    {
        $this->ad_window_id = $ad_window_id;

        return $this;
    }

    public function getAdWindowAccessUu(): ?string
    {
        return $this->ad_window_access_uu;
    }

    public function setAdWindowAccessUu(string $ad_window_access_uu): self
    {
        $this->ad_window_access_uu = $ad_window_access_uu;

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

    public function getIsreadwrite(): ?bool
    {
        return ($this->isreadwrite === 'Y');
    }

    public function setIsreadwrite(string $isreadwrite): self
    {
        $this->isreadwrite = $isreadwrite;

        return $this;
    }
}
