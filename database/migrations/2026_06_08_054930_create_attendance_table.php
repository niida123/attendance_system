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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id('attendance_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('holiday_id')->nullable();
            $table->date('attendance_date');

            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();

            $table->decimal('working_hours', 5, 2)->nullable();
            $table->integer('late_minutes')->nullable();
            $table->decimal('overtime_hours', 5, 2)->nullable();
            $table->enum('status', ['Present', 'Absent', 'Late', 'Leave', 'Holiday']);
            $table->string('remarks', 255)->nullable();

            $table->timestamps();

            $table->foreign('employee_id')
                ->references('employee_id')->on('employees')
                ->onDelete('cascade');

            $table->foreign('holiday_id')
                ->references('holiday_id')->on('holidays')
                ->onDelete('set null');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
