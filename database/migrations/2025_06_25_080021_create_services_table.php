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
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')->constrained('users');
            $table->foreignUuid('category_id')->constrained('service_categories');

            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('duration_minutes');
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_approved')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
