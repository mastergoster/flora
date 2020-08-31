<?php

namespace App\Model\Table;

use App\Model\Entity\InvocesLinesEntity;
use Core\Model\Table;

class ProductsTable extends Table
{
    public function findForInvoce($id, $column = 'id')
    {
        if (\is_array($id)) {
            foreach ($id as $k => $v) {
                $sql[] = "$k=:$k";
            }
            $sql = join(" AND ", $sql);
        } else {
            $sql = "$column=?";
            $id = [$id];
        }
        return $this->query("SELECT * FROM {$this->table} WHERE $sql", $id, true, InvocesLinesEntity::class);
    }
}
