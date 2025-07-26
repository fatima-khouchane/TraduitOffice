<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fichier_demandes', function (Blueprint $table) {
            Schema::table('fichier_demandes', function (Blueprint $table) {
                // Supprimer la clé étrangère existante si elle existe (à adapter selon ton cas)
                $table->dropForeign(['demande_id']);

                // Recréer la clé étrangère avec ON DELETE CASCADE
                $table->foreign('demande_id')
                    ->references('id')
                    ->on('demandes')
                    ->onDelete('cascade');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fichier_demandes', function (Blueprint $table) {
            Schema::table('fichier_demandes', function (Blueprint $table) {
                // Supprimer la FK cascade pour rollback
                $table->dropForeign(['demande_id']);

                // Recréer la FK sans cascade (si nécessaire)
                $table->foreign('demande_id')
                    ->references('id')
                    ->on('demandes');
            });
        });
    }
};
