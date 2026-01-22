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
        Schema::create('carte', function (Blueprint $table) {
            $table->id('id_carte');
            $table->uuid('uuid_carte')->unique()->default(DB::raw('gen_random_uuid()'));
            $table->string('numero_carte', 20)->unique();
            $table->unsignedBigInteger('id_type_carte');
            $table->unsignedBigInteger('id_banque');
            $table->unsignedBigInteger('id_grossiste');
            $table->date('date_expiration');
            $table->enum('statut_carte', ['ENREGISTREE', 'ACTIVE', 'BLOQUEE', 'EXPIREE', 'ANNULEE'])->default('ENREGISTREE');
            $table->timestamp('date_enregistrement')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_type_carte', 'fk_carte_type')
                  ->references('id_type_carte')
                  ->on('type_carte')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');

            $table->foreign('id_banque', 'fk_carte_banque')
                  ->references('id_banque')
                  ->on('banque')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');

            $table->foreign('id_grossiste', 'fk_carte_grossiste')
                  ->references('id_grossiste')
                  ->on('grossiste')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');

            // Index
            $table->index('numero_carte');
            $table->index('statut_carte');
            $table->index('date_expiration');
            $table->index('id_grossiste');
            $table->index('id_banque');
            $table->index('id_type_carte');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carte');
    }
};
