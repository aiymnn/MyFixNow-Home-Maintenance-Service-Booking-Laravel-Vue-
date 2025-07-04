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
        Schema::create('receipts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->string('pdf_path');
            $table->decimal('amount', 10, 2);
            $table->json('details')->nullable(); // for tax breakdown, metadata if needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
