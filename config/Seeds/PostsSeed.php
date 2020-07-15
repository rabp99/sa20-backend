<?php
use Migrations\AbstractSeed;

/**
 * Posts seed.
 */
class PostsSeed extends AbstractSeed
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
        
        for ($i = 0; $i < 200; $i++) {
            $data[] = [
                'user_id' => $faker->numberBetween(2, 90),
                'category_id' => $faker->numberBetween(1, 100),
                'title' => $faker->text(60),
                'subtitle' => $faker->text(180),
                'resumen' => $faker->text(300),
                'body' => $faker->randomHtml(),
                'portada' => $faker->text(60),
                'fechaRegistro' => $faker->date(),
                'fechaPublicacion' => $faker->date(),
                'fechaModificacion' => $faker->date(),
                'vistos' => $faker->numberBetween(100, 50000),
                'estado_id' => 1
            ];
        }
        
        $table = $this->table('posts');
        $table->insert($data)->save();
    }
}
