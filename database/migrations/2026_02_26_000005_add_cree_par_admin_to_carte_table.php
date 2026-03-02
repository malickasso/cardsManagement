<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('carte', function (Blueprint $table) {
            $table->unsignedBigInteger('cree_par_admin')->nullable()->after('id_carte');
            $table->index('cree_par_admin');
            $table->foreign('cree_par_admin')
                ->references('id_admin')
                ->on('admins')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carte', function (Blueprint $table) {
            $table->dropForeign(['cree_par_admin']);
            $table->dropIndex(['cree_par_admin']);
            $table->dropColumn('cree_par_admin');
        });
    }
};
