<?php
use Migrations\AbstractSeed;

/**
 * Roles seed.
 */
class RolesSeed extends AbstractSeed
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
        $data = [
            [
                'descripcion' => "Administrador",
                'estado_id' => 1
            ],
            [
                'descripcion' => "Usuario",
                'estado_id' => 1
            ]
        ];
        
        $table = $this->table('roles');
        $table->insert($data)->save();
    }
}
