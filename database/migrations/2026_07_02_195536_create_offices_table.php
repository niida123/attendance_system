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
        Schema::create('offices', function (Blueprint $table) {
            $table->id('office_id');

            $table->string('office_code', 20)->unique();
            $table->string('office_name', 100);
            $table->string('address', 255);

            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);

            $table->integer('allowed_radius')
                  ->comment('Allowed distance in meters');

            $table->string('office_ip', 45)->nullable();
            $table->string('office_wifi_name', 100)->nullable();
            $table->string('description')->nullable();

            $table->enum('status', ['Active', 'Inactive'])
                  ->default('Active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};