<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            $table->string('ticket_couleur', 7)->default('#4f46e5')
                  ->after('statut')
                  ->comment('Couleur propre à ce type de billet (VIP=or, Standard=bleu...)');
        });
    }

    public function down(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            $table->dropColumn('ticket_couleur');
        });
    }
};
