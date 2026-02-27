<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staircase_id')->constrained()->cascadeOnDelete();
            $table->string('number');
            $table->integer('floor');
            $table->foreignId('suite_type_id')->constrained()->restrictOnDelete(); // IMPORTANT
            $table->decimal('share_quota', 10, 6)->default(0);
            $table->integer('persons_count')->default(0);
            $table->boolean('has_central_heating')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        
            $table->unique(['association_id', 'staircase_id', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suites');
    }
};