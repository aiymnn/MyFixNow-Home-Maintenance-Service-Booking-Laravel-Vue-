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
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('customer_id')->constrained('users');
            $table->foreignUuid('provider_id')->constrained('users');
            $table->foreignUuid('service_id')->constrained('services');
            $table->date('date');
            $table->string('time_slot');
            $table->decimal('price_at_booking', 10, 2);
            $table->enum('status', ['pending', 'accepted', 'rejected', 'in_progress', 'completed', 'cancelled']);
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
