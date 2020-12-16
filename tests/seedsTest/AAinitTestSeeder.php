<?php


use Phinx\Seed\AbstractSeed;

class AAInitTestSeeder extends AbstractSeed
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
                'password'    => password_hash('password', PASSWORD_BCRYPT),
                'email'    => "test@test.fr",
                'first_name'    => 'nameTest',
                'last_name'    => 'lastNameTest',
                'pin'    => '1234',
                'token'    => '987654321',
                'phone_number'    => '0606060606',
                'activate'    => '1',
                'verify'    => '1'
            ],
            [
                'password'    => password_hash('password', PASSWORD_BCRYPT),
                'email'    => "test2@test.fr",
                'first_name'    => 'nameTest',
                'last_name'    => 'lastNameTest',
                'pin'    => '1234',
                'token'    => '987654321',
                'phone_number'    => '0606060607',
                'activate'    => '1',
                'verify'    => '1'
            ],
            [
                'password'    => password_hash('password', PASSWORD_BCRYPT),
                'email'    => "test3@test.fr",
                'first_name'    => 'nameTest',
                'last_name'    => 'lastNameTest',
                'pin'    => '1234',
                'token'    => '987654321',
                'phone_number'    => '0606060608',
                'activate'    => '0',
                'verify'    => '0'
            ],
            [
                'password'    => password_hash('password', PASSWORD_BCRYPT),
                'email'    => "test4@test.fr",
                'first_name'    => 'nameTest',
                'last_name'    => 'lastNameTest',
                'pin'    => '1234',
                'token'    => '987654321',
                'phone_number'    => '0606060609',
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
                'name'    => "administrateur",
                'level'    => 60,
                'activate'    => true
            ]
        ];
        $rolesLog = [
            [
                'id_users' => "1",
                'id_roles' => "4"
            ],
            [
                'id_users' => "2",
                'id_roles' => "1"
            ],
            [
                'id_users' => "3",
                'id_roles' => "3"
            ],
            [
                'id_users' => "4",
                'id_roles' => "3",
                "created_at" => "2020-12-10 15:50:36"
            ]
        ];


        $this->insert('users', $users);
        $this->insert('roles', $roles);
        $this->insert('roles_log', $rolesLog);
        $rolesLog2 = [
            [
                'id_users' => "4",
                'id_roles' => "2",
                "created_at" => "2020-12-10 15:54:36"
            ]
        ];
        $this->insert('roles_log', $rolesLog2);
    }
}
