<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('water_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suite_id')->constrained()->cascadeOnDelete();
            $table->date('reading_date');
            $table->decimal('cold_water', 10, 2)->default(0);
            $table->decimal('hot_water', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['suite_id', 'reading_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('water_readings');
    }
};