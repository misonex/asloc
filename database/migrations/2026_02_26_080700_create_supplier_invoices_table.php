<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->date('issue_date');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_amount', 12, 2);
            $table->string('document_path')->nullable();
            $table->enum('status', ['draft', 'finalized'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_invoices');
    }
};