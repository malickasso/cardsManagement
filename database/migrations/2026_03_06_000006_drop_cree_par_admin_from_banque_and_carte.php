<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE banque DROP CONSTRAINT IF EXISTS banque_cree_par_admin_foreign');
        DB::statement('DROP INDEX IF EXISTS banque_cree_par_admin_index');
        DB::statement('ALTER TABLE banque DROP COLUMN IF EXISTS cree_par_admin');

        DB::statement('ALTER TABLE carte DROP CONSTRAINT IF EXISTS carte_cree_par_admin_foreign');
        DB::statement('DROP INDEX IF EXISTS carte_cree_par_admin_index');
        DB::statement('ALTER TABLE carte DROP COLUMN IF EXISTS cree_par_admin');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration: ownership columns intentionally removed.
    }
};
