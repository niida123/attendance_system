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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('employee_code', 20)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);

            $table->enum('gender', ['Male', 'Female']);

            $table->date('date_of_birth')->nullable();

            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->unique()->nullable();

            $table->string('address', 255)->nullable();
            $table->string('photo', 255)->nullable();

            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('position_id');

            $table->date('hire_date');
            $table->decimal('basic_salary', 12, 2)->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            $table->foreign('department_id')
                ->references('id')->on('departments')
                ->onDelete('restrict');

            $table->foreign('position_id')
                ->references('id')->on('positions')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
