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
        Schema::table('evenements', function (Blueprint $table) {
            if (!Schema::hasColumn('evenements', 'categorie_id')) {
                $table->foreignId('categorie_id')
                      ->nullable()
                      ->after('categorie')
                      ->constrained('categories')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            if (Schema::hasColumn('evenements', 'categorie_id')) {
                $table->dropForeign(['categorie_id']);
                $table->dropColumn('categorie_id');
            }
        });
    }
};
