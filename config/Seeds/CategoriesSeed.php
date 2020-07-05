<?php
use Migrations\AbstractSeed;

/**
 * Categories seed.
 */
class CategoriesSeed extends AbstractSeed
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
        
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'descripcion' => $faker->text(30),
                'resumen' => $faker->text(60),
                'portada' => $faker->text(60),
                'estado_id' => 1
            ];
        }
        
        $table = $this->table('categories');
        $table->insert($data)->save();
    }
}
