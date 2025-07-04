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
        Schema::create('service_area_services', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('service_id')->constrained('services');
            $table->foreignUuid('area_id')->constrained('service_areas');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_area_services');
    }
};
