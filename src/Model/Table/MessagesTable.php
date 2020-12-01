<?php

namespace App\Model\Table;

use Core\Model\Table;

class MessagesTable extends Table
{
    
    public function messagesByIdUserAndIdRole($id_roles, $id_users)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE id_roles <= ? OR id_users = ? ORDER BY created_at DESC", [$id_roles, $id_users]);
    }


}
