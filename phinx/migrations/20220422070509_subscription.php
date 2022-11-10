<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Subscription extends AbstractMigration
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
        $users = $this->table('subscription');
        $users
            ->addColumn('id_users', 'integer', ['limit' => 10, 'null' => true])
            ->addColumn('started_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('finished_at', 'datetime', ['null' => true])
            ->addColumn('id_products', 'string', ['limit' => 100])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('id_users', 'users', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->addForeignKey('id_products', 'products', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->save();

        $products = $this->table('products');
        $products
            ->addColumn('subscribeable', 'boolean', ['default' => 'false', 'null' => true])
            ->addColumn('duration', 'integer', ['limit' => 10, 'null' => true])
            ->addColumn('unity', 'string', ['limit' => 100, 'null' => true])
            ->update();
    }
}
