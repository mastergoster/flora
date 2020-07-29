<?php

namespace App\Model\Table;

use Core\Model\Table;

class ForfaitLogTable extends Table
{
    public function findAllByIdUser(int $id): array
    {
        return $this->query("SELECT * FROM {$this->table} WHERE id_user=? ORDER BY created_at DESC", [$id]);
    }
}
