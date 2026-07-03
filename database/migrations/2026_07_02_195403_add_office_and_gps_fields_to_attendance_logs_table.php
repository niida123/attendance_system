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
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('office_id')->after('employee_id');

            $table->decimal('latitude', 10, 8)->nullable()->after('ip_address');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->decimal('distance_from_office', 8, 2)->nullable()->after('longitude');

            $table->boolean('is_verified')->default(false)->after('distance_from_office');

            // Foreign Key
            $table->foreign('office_id')
                  ->references('office_id')
                  ->on('offices')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->dropForeign(['office_id']);

            $table->dropColumn([
                'office_id',
                'latitude',
                'longitude',
                'distance_from_office',
                'is_verified',
            ]);
        });
    }
};