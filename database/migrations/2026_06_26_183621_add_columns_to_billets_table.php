<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            $table->integer('quantite_totale')->default(0);
            $table->integer('quantite_disponible')->default(0);
            $table->integer('quantite_vendue')->default(0);
            $table->text('description')->nullable();
            $table->string('statut')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            $table->dropColumn(['quantite_totale', 'quantite_disponible', 'quantite_vendue', 'description', 'statut']);
        });
    }
};
