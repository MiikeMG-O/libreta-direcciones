<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Email;
use App\Models\Contacto;
use App\Models\Telefono;
use App\Models\Direccion;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $usuarios = User::factory()->count(10)->create();

        foreach ($usuarios as $usuario) {
            $contactos = Contacto::factory()->count(500)->create(['user_id' => $usuario->id]);

            foreach ($contactos as $contacto) {
                Telefono::factory()->count(3)->create(['contacto_id' => $contacto->id]);
                Email::factory()->count(3)->create(['contacto_id' => $contacto->id]);
                Direccion::factory()->count(3)->create(['contacto_id' => $contacto->id]);
            }
        }
    }
}
