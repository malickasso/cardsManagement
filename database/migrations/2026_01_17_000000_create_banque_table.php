<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banque', function (Blueprint $table) {
            $table->id('id_banque');
            $table->uuid('uuid_banque')->unique()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_banque', 150);
            $table->string('code_banque', 50)->unique();
            $table->enum('statut', ['ACTIF', 'INACTIF'])->default('ACTIF');
            $table->timestamps();

            // Index
            $table->index('code_banque');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banque');
    }
};
