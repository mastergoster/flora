<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class Products extends AbstractMigration
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
        $products = $this->table('products');
        $products
            ->addColumn('ref', 'string', ['limit' => 255])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('desc', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('price', 'float', ['limit' => 10])
            ->addColumn('activate', 'boolean', ['default' => 'false'])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->save();


        $invoces = $this->table('invoces');
        $invoces
            ->addColumn('ref', 'string', ['limit' => 255])
            ->addColumn('id_users', 'integer', ['limit' => 10])
            ->addColumn('price', 'float', ['limit' => 10, 'null' => true])
            ->addColumn('activate', 'boolean', ['default' => false])
            ->addColumn('date_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('id_users', 'users', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->save();

        $invoces_lines = $this->table('invoces_lines');
        $invoces_lines
            ->addColumn('id_invoces', 'integer', ['limit' => 10])
            ->addColumn('id_products', 'integer', ['limit' => 10, 'null' => true])
            ->addColumn('ref', 'string', ['limit' => 255])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('desc', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('price', 'float', ['limit' => 10])
            ->addColumn('qte', 'float', ['limit' => 10])
            ->addColumn('discount', 'float', ['limit' => 10])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('id_products', 'products', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
            ->addForeignKey('id_invoces', 'invoces', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->save();
    }
}
