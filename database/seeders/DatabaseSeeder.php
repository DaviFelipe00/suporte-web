<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    \App\Models\User::factory()->create([
        'name' => 'Administrador Simplemind',
        'email' => 'admin@simplemind.com.br',
        'password' => bcrypt('simplemind@2026'),
    ]);
}
}
