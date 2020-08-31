<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class UserUpdate extends AbstractMigration
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
        $users = $this->table('users');
        $users
            ->addColumn('postal_code', 'string', ['null' => true])
            ->addColumn('street', 'string', ['null' => true])
            ->addColumn('city', 'string', ['null' => true])
            ->addColumn('desc', 'text', ['null' => true, 'limit' => MysqlAdapter::TEXT_LONG])
            ->addIndex(['phone_number'], ['unique' => true])
            ->update();
    }
}
