<?php

namespace App\Model\Table;

use Core\Model\Table;


class InvocesTable extends Table
{

    public function all($order = false, $colomun = 'id')
    {
        $all = parent::all();
        foreach ($all as $value) {
            $value->setInvocesLines((new InvocesLinesTable($this->db))->findAll($value->getId(), "id_invoces"));
        }
        return $all;
    }

    public function findActivate($id)
    {
        $all = $this->query("SELECT * FROM {$this->table} WHERE id=? AND activate=1", [$id], true);
        if ($all) {
            $all->setInvocesLines((new InvocesLinesTable($this->db))->findAll($all->getId(), "id_invoces"));
        }

        return $all;
    }
    public function allActivateByUser($id)
    {
        $all = $this->query("SELECT * FROM {$this->table} WHERE id_users=? AND activate=1", [$id]);
        return $all;
    }
}
