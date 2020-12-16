<?php

namespace App\Model\Table;

use Core\Model\Table;

class MessagesTable extends Table
{
    public function messagesByIdUserAndLevelUser(int $id_users, int $level_user, string $mail_user)
    {
        return $this->query(
            "SELECT m.*, r.level, u.id as idExp
            FROM messages as m 
            LEFT JOIN roles as r ON m.id_roles = r.id 
            LEFT JOIN users as u ON m.email = u.email 
            WHERE m.id_users = ? OR r.level <= ? OR m.email = ?
            ORDER BY created_at DESC",
            [$id_users, $level_user, $mail_user]
        );
    }
}
