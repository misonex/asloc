<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suite_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suite_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->uuid('token')->unique();
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table->index(['suite_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suite_invitations');
    }
};