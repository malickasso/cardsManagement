<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('partenaire', function (Blueprint $table) {
            $table->bigIncrements('id_partenaire');
            $table->uuid('uuid_partenaire')->default(DB::raw('gen_random_uuid()'))->unique();
            $table->bigInteger('id_grossiste');
            $table->string('raison_sociale', 150);
            $table->string('nom_proprietaire', 100);
            $table->string('prenom_proprietaire', 100);
            $table->string('ifu', 50)->unique();
            $table->string('rccm', 50)->unique();
            $table->string('telephone', 30)->nullable();
            $table->string('email', 150)->unique();
            $table->string('quartier', 100)->nullable();
            $table->text('description')->nullable();
            $table->text('mot_de_passe');
            $table->decimal('solde', 15, 2)->default(0)->check('solde >= 0');
            $table->timestamp('date_creation')->useCurrent();
            $table->enum('statut_general', ['ACTIF', 'INACTIF'])->default('ACTIF');

            // Foreign key
            $table->foreign('id_grossiste')->references('id_grossiste')->on('grossiste')->onDelete('cascade');

            // Index sur uuid
            $table->index('uuid_partenaire');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partenaire');
    }
};
