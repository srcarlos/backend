<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UsersTableSeeder::class);
        //$this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        //$this->call(PermissionRoleTableSeeder::class);
        $this->call(DataProvinceCitiesTableSeeder::class);

        // Configuracion basica del sistema
        $this->call(ConfZonasTableSeeder::class);
        $this->call(ConfUnidadMedidaTableSeeder::class);
        $this->call(ConfTiposMovimientoTableSeeder::class);
        $this->call(ConfImpuestosTableSeeder::class);
        $this->call(ConfCategoriasTableSeeder::class);
        $this->call(ConfPlanesTableSeeder::class);
        $this->call(ConfInsumosTableSeeder::class);
        $this->call(ConfIngredientesTableSeeder::class);
        $this->call(ConfPlatosTableSeeder::class);
        $this->call(ConfCompaniasTableSeeder::class);
        $this->call(ConfCentrosTableSeeder::class);
        $this->call(ConfCocinasTableSeeder::class);
        $this->call(ConfBodegasTableSeeder::class);
        $this->call(ConfSeccionesTableSeeder::class);
        $this->call(ConfPosicionesTableSeeder::class);
        Model::reguard();
    }
}
