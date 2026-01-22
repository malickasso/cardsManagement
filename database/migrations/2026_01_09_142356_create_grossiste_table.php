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
        Schema::create('grossiste', function (Blueprint $table) {
            $table->bigIncrements('id_grossiste');
            $table->uuid('uuid_grossiste')->default(DB::raw('gen_random_uuid()'))->unique();
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
            $table->bigInteger('cree_par_admin');
            $table->timestamp('date_creation')->useCurrent();
            $table->enum('statut_general', ['ACTIF', 'INACTIF'])->default('ACTIF');

            // Foreign key
            $table->foreign('cree_par_admin')->references('id_admin')->on('admins')->onDelete('cascade');
            
            // Index sur uuid
            $table->index('uuid_grossiste');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grossiste');
    }
};
