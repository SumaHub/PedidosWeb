<?php

namespace App\Entity\Main;

use App\Repository\Main\CLocationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CLocationRepository::class)
 * @ORM\Table(name="c_location")
 */
class CLocation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $c_location_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address1;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_city_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_country_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $c_region_id;

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): self
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getCCityId(): ?int
    {
        return $this->c_city_id;
    }

    public function setCCityId(int $c_city_id): self
    {
        $this->c_city_id = $c_city_id;

        return $this;
    }

    public function getCCountryId(): ?int
    {
        return $this->c_country_id;
    }

    public function setCCountryId(int $c_country_id): self
    {
        $this->c_country_id = $c_country_id;

        return $this;
    }

    public function getCLocationId(): ?int
    {
        return $this->c_location_id;
    }

    public function setCLocationId(int $c_location_id): self
    {
        $this->c_location_id = $c_location_id;

        return $this;
    }

    public function getCRegionId(): ?int
    {
        return $this->c_region_id;
    }

    public function setCRegionId(int $c_region_id): self
    {
        $this->c_region_id = $c_region_id;

        return $this;
    }
}
