<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class V20200813111853 extends AbstractMigration
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
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('first_name', 'string', ['limit' => 30])
            ->addColumn('last_name', 'string', ['limit' => 30])
            ->addColumn('pin', 'string', ['limit' => 8])
            ->addColumn('token', 'string', ['limit' => 8])
            ->addColumn('phone_number', 'string', ['limit' => 10])
            ->addColumn('activate', 'boolean', ['default' => '0'])
            ->addColumn('verify', 'boolean', ['default' => '0'])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['email'], ['unique' => true])
            ->save();

        $packages = $this->table('packages');
        $packages
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('duration', 'integer', ['limit' => 10])
            ->addColumn('price', 'integer', ['limit' => 10])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])

            ->save();

        $packagesLog = $this->table('packages_log');
        $packagesLog
            ->addColumn('id_users', 'integer', ['limit' => 10])
            ->addColumn('id_packages', 'integer', ['limit' => 10])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('id_users', 'users', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->addForeignKey('id_packages', 'packages', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->save();

        $hour = $this->table('hours');
        $hour
            ->addColumn('id_users', 'integer', ['limit' => 10])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('id_users', 'users', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->save();

        $roles = $this->table('roles');
        $roles
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('level', 'integer', ['limit' => 10, 'default' => 0])
            ->addColumn('activate', 'boolean', ['default' => 'false'])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->save();

        $rolesLog = $this->table('roles_log');
        $rolesLog
            ->addColumn('id_users', 'integer', ['limit' => 10])
            ->addColumn('id_roles', 'integer', ['limit' => 10])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('id_users', 'users', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->addForeignKey('id_roles', 'roles', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->save();

        $comptaLines = $this->table('compta_lines');
        $comptaLines
            ->addColumn('desc', 'string', ['limit' => 255])
            ->addColumn('debit', 'float', ['limit' => 10])
            ->addColumn('credit', 'float', ['limit' => 10])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->save();

        $comptaNdf = $this->table('compta_ndf');
        $comptaNdf
            ->addColumn('desc', 'string', ['limit' => 255])
            ->addColumn('debit', 'float', ['limit' => 10])
            ->addColumn('credit', 'float', ['limit' => 10])
            ->addColumn('id_users', 'integer', ['limit' => 10])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('id_users', 'users', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->save();
    }
}
