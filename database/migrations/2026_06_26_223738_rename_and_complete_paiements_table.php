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
        Schema::rename('paiememts', 'paiements');

        Schema::table('paiements', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('evenement_id')->nullable()->constrained('evenements')->nullOnDelete();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('reference')->unique()->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['evenement_id']);
            $table->dropColumn(['user_id', 'evenement_id', 'amount', 'status', 'payment_method', 'reference']);
        });

        Schema::rename('paiements', 'paiememts');
    }
};
