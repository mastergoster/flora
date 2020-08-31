<?php


use Phinx\Seed\AbstractSeed;

class CAInvoceSeeder extends AbstractSeed
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
            $invoce = [];
            $lines = [];
            $products = [];
            for ($k = 0; $k <= 5; $k++) {
                $products[] = [
                    "ref" => $faker->domainWord(),
                    "name" => $faker->sentence(1),
                    "desc" => $faker->paragraph(),
                    "price" => $faker->randomNumber(2),
                    "activate" => true,
                ];
            }
            for ($i = 0; $i <= 3; $i++) {
                $invoce[] = [
                    'ref' =>  "PROV_" . $faker->dateTimeBetween('now', '+1 years')->format("Y-m-d H:i:s"),
                    'id_users' => 1,
                    'date_at' => $faker->dateTimeBetween('now', '+1 years')->format("Y-m-d H:i:s"),
                ];
                for ($j = 0; $j <= 5; $j++) {
                    $lines[] = [
                        "id_invoces" => $i,
                        "id_products" => 0,
                        "ref" => $faker->domainWord(),
                        "name" =>  $faker->sentence(1),
                        "desc" => $faker->paragraph(),
                        "price" => $faker->randomNumber(2),
                        "qte" => $faker->randomNumber(1),
                        "discount" => "0"
                    ];
                }
            }


            $this->insert('invoces', $invoce);
            $this->insert('invoces_lines', $lines);
            $this->insert('products', $products);
        }
    }
}
