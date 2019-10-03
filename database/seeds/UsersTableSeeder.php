<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'nombre' => 'admin',
            'apellido' => 'istrador',
            'email' => 'admin@example.com',
            'telefono' => '04126996212',
            'password' => bcrypt('password'),
            'email_verified' => '1',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'nombre' => 'usuario',
            'apellido' => '1',
            'email' => 'usuario1@example.com',
            'telefono' => '04126996512',
            'password' => bcrypt('password'),
            'email_verified' => '1',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
    }
}
