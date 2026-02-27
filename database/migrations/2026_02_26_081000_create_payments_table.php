<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suite_id')->constrained()->cascadeOnDelete();
            $table->foreignId('maintenance_list_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('paid_at')->nullable();
            $table->string('method')->nullable(); // cash, transfer etc.
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};