<?php

namespace App\Jaxon;

use App\Model\Dashboard as ModelDashboard;

class Dashboard extends Base
{
    public static function stats(){ return ModelDashboard::get_stats(); }
}

?>