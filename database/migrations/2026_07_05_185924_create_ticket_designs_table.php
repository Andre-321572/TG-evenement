<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evenement_id')->constrained('evenements')->onDelete('cascade');

            // Logo personnalisé
            $table->string('logo')->nullable()->comment('Fichier logo uploadé');

            // Fond du ticket
            $table->enum('fond_type', ['couleur', 'degrade', 'image'])->default('couleur');
            $table->string('fond_couleur1', 7)->default('#4f46e5')->comment('Couleur principale ou début dégradé');
            $table->string('fond_couleur2', 7)->nullable()->comment('Fin de dégradé');
            $table->string('fond_image')->nullable()->comment('Image de fond uploadée');
            $table->unsignedTinyInteger('fond_opacite')->default(100)->comment('Opacité 0-100');

            // Typographie
            $table->string('police', 100)->default('Outfit')->comment('Famille de police Google Fonts');
            $table->string('couleur_titre', 7)->default('#ffffff')->comment('Couleur du titre');
            $table->string('couleur_texte', 7)->default('#e2e8f0')->comment('Couleur du texte secondaire');

            // Template de base
            $table->enum('template', ['classique', 'moderne', 'festif'])->default('moderne');

            $table->timestamps();

            $table->unique('evenement_id'); // un seul design par événement
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_designs');
    }
};
