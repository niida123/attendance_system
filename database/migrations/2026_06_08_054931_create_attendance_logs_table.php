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
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('employee_id');

            $table->dateTime('log_datetime');

            $table->enum('log_type', ['Check In', 'Check Out']);

            $table->string('device_name', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('gps_location', 255)->nullable();

            $table->timestamps();

            // Foreign key
            $table->foreign('employee_id')
                ->references('employee_id')
                ->on('employees')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
