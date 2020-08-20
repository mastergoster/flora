<?php


use Phinx\Seed\AbstractSeed;

class BBEventSeeder extends AbstractSeed
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
            $events = [];
            for ($i = 0; $i <= 20; $i++) {
                $events[] = [
                    'title' => $faker->sentence(3),
                    'slug' => $faker->slug(3),
                    'description' => $faker->paragraph(),
                    'cover' => "https://picsum.photos/id/" . $i . "/300/200/?blur=2",
                    'date_at' => $faker->dateTimeBetween('now', '+1 years')->format("Y-m-d H:i:s"),
                    'publish' => $faker->boolean(50)
                ];
            }


            $this->insert('events', $events);
        }
    }
}
