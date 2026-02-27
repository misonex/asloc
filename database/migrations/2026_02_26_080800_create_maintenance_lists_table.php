<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->enum('status', ['draft', 'published', 'locked'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_lists');
    }
};