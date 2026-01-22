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
        Schema::create('type_carte', function (Blueprint $table) {
            $table->id('id_type_carte');
            $table->uuid('uuid_type_carte')->unique()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_type_carte', 100);
            $table->text('description')->nullable();
            $table->enum('statut', ['ACTIF', 'INACTIF'])->default('ACTIF');
            $table->timestamps();

            // Index
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_carte');
    }
};
