<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Booking extends AbstractMigration
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
        $this->table('booking_events')
            ->addColumn('id_events', 'integer', ['limit' => 10])
            ->addColumn('email', 'string', ['limit' => 255])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('id_events', 'events', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->save();
    }
}
