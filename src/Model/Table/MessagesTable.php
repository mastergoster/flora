<?php

namespace App\Model\Table;

use Core\Model\Table;

class MessagesTable extends Table
{
    public function messagesByIdUserAndLevelUser(int $id_users, int $level_user)
    {
        return $this->query(
            "SELECT m.*, r.level
            FROM messages as m 
            LEFT JOIN roles as r ON m.id_roles = r.id 
            WHERE m.id_users = ? OR r.level <= ?
            ORDER BY created_at DESC",
            [$id_users, $level_user]
        );
    }
}
