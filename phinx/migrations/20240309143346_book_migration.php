<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class BookMigration extends AbstractMigration
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
        $books = $this->table('books');
        $books
            ->addColumn('start_at', 'datetime')
            ->addColumn('end_at', 'datetime')
            ->addColumn('id_user', 'integer')
            ->addForeignKey('id_user', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addColumn('id_ressource', 'integer')
            ->addForeignKey('id_ressource', 'ressources', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->save();

        $this->table('ressources')
            ->addColumn('color', 'string', ['limit' => 255, 'null' => true])
            ->update();
    }
}
