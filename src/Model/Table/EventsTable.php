<?php

namespace App\Model\Table;

use Core\Model\Table;
use Symfony\Component\VarDumper\Cloner\Data;

class EventsTable extends Table
{
    public function allfutur()
    {
        $date = date("Y-m-d H:i:s");
        return $this->query("SELECT * FROM $this->table WHERE publish= 1 AND `date_at`>= '$date'");
    }
}
