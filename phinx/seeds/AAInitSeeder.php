<?php


use Phinx\Seed\AbstractSeed;

class AAInitSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $users = [
            [
                'password'    => password_hash(getenv("passwordadmin"), PASSWORD_BCRYPT),
                'email'    => getenv('usernameadmin'),
                'first_name'    => 'foo',
                'last_name'    => 'foo',
                'pin'    => 'foo',
                'token'    => 'foo',
                'phone_number'    => 'foo',
                'activate'    => '1',
                'verify'    => '1'
            ]
        ];

        $roles = [
            [
                'name'    => "aucun",
                'level'    => 0,
                'activate'    => true
            ],
            [
                'name'    => "adherants",
                'level'    => 20,
                'activate'    => true
            ],
            [
                'name'    => "membre actif",
                'level'    => 40,
                'activate'    => true
            ],
            [
                'name'    => "bureau",
                'level'    => 50,
                'activate'    => true
            ],
            [
                'name'    => "administrateur",
                'level'    => 60,
                'activate'    => true
            ]
        ];
        $rolesLog = [
            [
                'id_users' => "1",
                'id_roles' => "5"
            ]
        ];


        $this->insert('users', $users);
        $this->insert('roles', $roles);
        $this->insert('roles_log', $rolesLog);
    }
}
