<?php


use Phinx\Seed\AbstractSeed;

class SubscriptionSeeder extends AbstractSeed
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
            $subscription = [];
            for ($i = 1; $i <= 6; $i++) {
                $subscription[] = [
                    'id_users' => $i,
                    'started_at' => $faker->dateTimeBetween('-1 years', 'now')->format("Y-m-d H:i:s"),
                    'finished_at' => $faker->dateTimeBetween('now', '+6 months')->format("Y-m-d H:i:s"),
                    'id_products' => 7,
                ];
            }
            $this->insert('subscription', $subscription);
        }
    }
}
