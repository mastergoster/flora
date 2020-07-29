<?php

namespace App\Model\Table;

use Core\Model\Table;

class ForfaitTable extends Table
{
    public function allAssocId()
    {
        $all = $this->all();
        foreach ($all as $forfait) {
            $return[$forfait->getId()] = $forfait;
        }
        return $return;
    }
}
