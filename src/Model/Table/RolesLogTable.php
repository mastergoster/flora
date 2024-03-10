<?php

namespace App\Model\Table;

use Core\Model\Table;

class RolesLogTable extends Table
{
    public function updateRole($userId, $newIdRole)
    {
        $last = end($this->findAll($userId, "id_users", true, "created_at"));
        $this->update($last->getId(), "id", ["updated_at" => date("Y-m-d H:i:s")]);
        $this->create(
            [
                "id_users" => $userId,
                "id_roles" => $newIdRole,
            ]
        );
    }
}
