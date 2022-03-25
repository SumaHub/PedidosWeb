<?php

namespace App\Jaxon;

interface Template
{    
    
    public function get(Int $id);

    public function getAll(Int $foreign_key);
}

?>