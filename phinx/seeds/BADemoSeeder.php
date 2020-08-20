<?php


use Phinx\Seed\AbstractSeed;

class BADemoSeeder extends AbstractSeed
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
        if (getenv("ENV_DEV")) {


            $faker = Faker\Factory::create('fr_FR');
            $users = [];
            for ($i = 0; $i < 20; $i++) {
                $users[] =
                    [
                        'password'    => password_hash("password", PASSWORD_BCRYPT),
                        'email'    => "user" . $i . "@test.fr",
                        'first_name'    => $faker->firstName,
                        'last_name'    => $faker->lastName,
                        'pin'    => $faker->randomNumber(4),
                        'token'    => $faker->randomNumber(5),
                        'phone_number'    => $faker->phoneNumber,
                        'activate'    => $faker->boolean(50),
                        'verify'    => $faker->boolean(50)
                    ];
            }
            $rolesLog = [];
            for ($f = 2; $f < 22; $f++) {
                $rolesLog[] =
                    [
                        'id_users' => $f,
                        'id_roles' => $faker->numberBetween(1, 3)
                    ];
            }


            $this->insert('users', $users);
            $this->insert('roles_log', $rolesLog);
        }
    }
}
