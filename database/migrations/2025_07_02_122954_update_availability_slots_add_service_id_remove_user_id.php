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
        Schema::table('availability_slots', function (Blueprint $table) {
            // Add service_id
            $table->foreignUuid('service_id')
                ->after('id')
                ->constrained('services');

            // Drop foreign key first, then drop user_id column
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('availability_slots', function (Blueprint $table) {
            // Re-add user_id
            $table->foreignUuid('user_id')
                ->after('id')
                ->constrained('users');

            // Drop foreign key first, then drop service_id
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });
    }
};
