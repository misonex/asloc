<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_list_id')->constrained()->cascadeOnDelete();
            $table->foreignId('suite_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->enum('calculation_type', [
                'per_person',
                'per_area',
                'per_share',
                'fixed'
            ])->nullable();
            $table->timestamps();

            $table->index(['maintenance_list_id', 'suite_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_items');
    }
};