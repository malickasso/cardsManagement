<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'uuid_admin'    => Str::uuid(),
                'nom'           => 'Admin',
                'prenom'        => 'Default',
                'email'         => 'admin@example.com',
                'telephone'     => null,
                'mot_de_passe'  => 'Admin@1234',
            ]
        );
    }
}
