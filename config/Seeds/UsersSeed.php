<?php
use Migrations\AbstractSeed;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run() {
        $faker = Faker\Factory::create();
        $data = [];
        $data[] = [
            'rol_id' => 1,
            'nombres' => "admin",
            'apellidos' => "admin",
            'username' => "admin",
            'password' => "$2y$10$ensUanaDuuxHdA8cnU3daODwIPXcp4nyt4s1aOtAQBNiFRiSmSCva",
            'twitter' => "...",
            'estado_id' => 1
        ];
        
        for ($i = 0; $i < 99; $i++) {
            $data[] = [
                'rol_id' => $faker->numberBetween(1, 2),
                'nombres' => $faker->name(90),
                'apellidos' => $faker->lastName(90),
                'username' => $faker->userName,
                'password' => $faker->password(60),
                'twitter' => $faker->userName,
                'estado_id' => 1
            ];
        }
        
        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
