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
        Schema::create('association_requests', function (Blueprint $table) {
            $table->id();
            $table->string('association_name');
            $table->string('association_address');
            $table->string('fiscal_code')->nullable();
            $table->string('manager_name');
            $table->string('manager_email');
            $table->json('metadata')->nullable(); // putem salva aici preset ales, opțiuni etc.
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'processed'
            ])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('manager_email');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('association_requests');
    }
};
