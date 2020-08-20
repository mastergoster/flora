<?php

namespace App\Model\Table;

use Core\Model\Table;

class HoursTable extends Table
{
    public function ajout(int $id_user)
    {

        $heure = date('Y-m-d H:i:s');
        $this->query("INSERT INTO {$this->table} (id_users) VALUES (:id_users)", [
            'id_users' => $id_user
        ]);
        return [
            'id' => $this->db->lastInsertId(),
            'id_user' => $id_user,
            'created_at' => $heure
        ];
    }

    public function presence($id): bool
    {
        $now = date('Y-m-d');
        $list = $this->findAll($id, 'id_users');
        $present = [];

        foreach ($list as $ligne) {
            if (substr($ligne->getCreatedAt()->format("Y-m-d"), 0, 10) == $now) {
                $present[] = $ligne;
            }
        }
        if (count($present) < 1) {
            return false;
        } elseif (count($present) % 2) {
            return true;
        } else {
            return false;
        }
    }
}
