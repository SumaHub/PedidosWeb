<?php

namespace App\Jaxon;

use App\Entity\MWarehouse;

class Warehouse extends Base implements Template
{
    public function get(int $id)
    {
        return $this->manager->getRepository(MWarehouse::class)->find($id);
    }

    public function getAll(int $foreign_key)
    {
        return $this->manager->getRepository(MWarehouse::class)->findAll();
    }
}

?>