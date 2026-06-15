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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id('shift_id');
            $table->string('shift_name', 100)->notNull();
            $table->time('start_time')->notNull();
            $table->time('end_time')->notNull();
            $table->integer('late_after_minutes')->notNull()->default(0);
            $table->integer('early_leave_before_minutes')->notNull()->default(0);
            $table->decimal('working_hours', 5, 2)->notNull()->default(0);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
