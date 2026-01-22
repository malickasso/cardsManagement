<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ============ PERMISSIONS ADMIN ============
        $adminPermissions = [
            'create-grossistes',
            'view-grossistes',
            'edit-grossistes',
            'delete-grossistes',
            'manage-admins',
        ];

        foreach ($adminPermissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // ============ PERMISSIONS GROSSISTE ============
        $grossistePermissions = [
            'create-partenaires',
            'view-partenaires',
            'edit-partenaires',
            'delete-partenaires',
            'create-cartes',
            'view-cartes',
            'affecter-cartes',
            'valider-activation',
            'valider-dotation',
            'valider-rechargement',
            'view-demandes-activation',
            'view-demandes-dotation',
            'view-demandes-rechargement',
        ];

        foreach ($grossistePermissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // ============ PERMISSIONS PARTENAIRE ============
        $partenairePermissions = [
            'view-own-cartes',
            'demander-activation',
            'demander-dotation',
            'demander-rechargement',
            'view-own-solde',
        ];

        foreach ($partenairePermissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // ============ CRÉATION DES RÔLES ============

        // Rôle Admin
        $adminRole = Role::create([
            'name' => 'admin',
            'guard_name' => 'admin'
        ]);
        $adminRole->givePermissionTo($adminPermissions);

        // Rôle Grossiste
        $grossisteRole = Role::create([
            'name' => 'grossiste',
            'guard_name' => 'web'
        ]);
        $grossisteRole->givePermissionTo($grossistePermissions);

        // Rôle Partenaire
        $partenaireRole = Role::create([
            'name' => 'partenaire',
            'guard_name' => 'web'
        ]);
        $partenaireRole->givePermissionTo($partenairePermissions);

        $this->command->info('Rôles et permissions créés avec succès!');
    }
}
