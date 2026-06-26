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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('categorie');
            $table->string('titre');
            $table->date('date');
            $table->time('start_heure');
            $table->time('end_heure');
            $table->string('lieu');
            $table->text('lien_google_map')->nullable();
            $table->string('photo')->nullable(); 
            $table->string('video')->nullable();
            $table->text('description')->nullable();
            $table->string('nom_proprietaire');
            $table->string('telephone');
            $table->string('email');
            $table->text('facebook')->nullable() ;
            $table->text('whatsapp')->nullable() ;
            $table->text('twiter')->nullable() ;
            $table->enum('statut',['en organisation','publier'])->default('en organisation');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
