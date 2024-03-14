<?php

namespace App\Model\Table;

use Core\Model\Table;

class InvocesTable extends Table
{

    public function all($order = false, $colomun = 'id')
    {
        $all = parent::all();
        foreach ($all as $value) {
            $value->setUser((new UsersTable($this->db))->find($value->getIdUsers()));
            $value->setPaiement((new ComptaLinesTable($this->db))->findAll($value->getRef(), "desc"));
            $value->setInvocesLines((new InvocesLinesTable($this->db))->findAll($value->getId(), "id_invoces"));
        }
        return $all;
    }

    public function findActivate($id)
    {
        $all = $this->query("SELECT * FROM {$this->table} WHERE id=? AND activate=1", [$id], true);
        if ($all) {
            $all->setPaiement((new ComptaLinesTable($this->db))->findAll($all->getRef(), "desc"));
            $all->setInvocesLines((new InvocesLinesTable($this->db))->findAll($all->getId(), "id_invoces"));
        }

        return $all;
    }

    public function findById($id)
    {
        $all = $this->query("SELECT * FROM {$this->table} WHERE id=?", [$id], true);
        if ($all) {
            $all->setUser((new UsersTable($this->db))->find($all->getIdUsers()));
            $all->setPaiement((new ComptaLinesTable($this->db))->findAll($all->getRef(), "desc"));
            $all->setInvocesLines((new InvocesLinesTable($this->db))->findAll($all->getId(), "id_invoces"));
        }

        return $all;
    }

    public function allActivateByUser($id)
    {
        $all = $this->query("SELECT * FROM {$this->table} WHERE id_users=? AND activate=1", [$id]);
        foreach ($all as $value) {
            $value->setUser((new UsersTable($this->db))->find($value->getIdUsers()));
            $value->setPaiement((new ComptaLinesTable($this->db))->findAll($value->getRef(), "desc"));
            $value->setInvocesLines((new InvocesLinesTable($this->db))->findAll($value->getId(), "id_invoces"));
        }
        return $all;
    }
}
