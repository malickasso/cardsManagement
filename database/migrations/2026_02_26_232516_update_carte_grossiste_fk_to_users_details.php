<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer l'ancienne FK vers grossiste, créer une nouvelle vers users_details
        DB::statement('ALTER TABLE carte DROP CONSTRAINT fk_carte_grossiste');
        DB::statement('ALTER TABLE carte ADD CONSTRAINT fk_carte_grossiste FOREIGN KEY (id_grossiste) REFERENCES users_details(id_user_detail) ON DELETE RESTRICT ON UPDATE CASCADE');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE carte DROP CONSTRAINT fk_carte_grossiste');
        DB::statement('ALTER TABLE carte ADD CONSTRAINT fk_carte_grossiste FOREIGN KEY (id_grossiste) REFERENCES grossiste(id_grossiste) ON DELETE RESTRICT ON UPDATE CASCADE');
    }
};
