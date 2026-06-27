<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('evenement_id')->constrained('evenements')->onDelete('cascade');
            $table->foreignId('billet_id')->constrained('billets')->onDelete('cascade');
            $table->string('stripe_session_id')->nullable();
            $table->string('buyer_email')->nullable();
            $table->string('buyer_name')->nullable();
            $table->boolean('is_scanned')->default(false);
            $table->timestamp('scanned_at')->nullable();
            $table->foreignId('scanned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_codes');
    }
};
