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
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('cin');
            $table->string('telephone');
            $table->date('date_debut');
            $table->date('date_fin');

            // Pour stocker plusieurs documents, on peut utiliser JSON (ou créer une table liée, mais ici on fait simple)
            $table->json('documents'); // contiendra les arrays ['categorie' => ..., 'sous_type' => ...]

            $table->decimal('prix_total', 8, 2);
            $table->string('langue_origine');
            $table->string('langue_souhaitee');
            $table->text('remarque')->nullable();

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
