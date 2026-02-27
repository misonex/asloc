<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suite_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('rooms');
            $table->decimal('area', 8, 2);
            $table->timestamps();

            $table->unique(['association_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suite_types');
    }
};
