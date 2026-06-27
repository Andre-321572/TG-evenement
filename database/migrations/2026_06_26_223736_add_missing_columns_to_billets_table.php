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
        Schema::table('billets', function (Blueprint $table) {
            if (!Schema::hasColumn('billets', 'quantite_totale')) {
                $table->integer('quantite_totale')->default(0);
            }
            if (!Schema::hasColumn('billets', 'quantite_disponible')) {
                $table->integer('quantite_disponible')->default(0);
            }
            if (!Schema::hasColumn('billets', 'quantite_vendue')) {
                $table->integer('quantite_vendue')->default(0);
            }
            if (!Schema::hasColumn('billets', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('billets', 'statut')) {
                $table->string('statut')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            $table->dropColumn(['quantite_totale', 'quantite_disponible', 'quantite_vendue', 'description', 'statut']);
        });
    }
};
