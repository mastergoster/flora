<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Ressouce extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->table('ressources')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('description', 'text')
            ->addColumn('nombre_place', 'integer', ['limit' => 10, 'null' => true])
            ->addColumn('slug', 'string', ['limit' => 255])
            ->addColumn('id_images', 'integer', ['limit' => 10, 'null' => true])
            ->addForeignKey('id_images', 'images', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->save();
    }
}
