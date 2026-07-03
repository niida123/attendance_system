<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('offices', function (Blueprint $table) {
            // Normalize existing data first
            DB::table('offices')->where('status', 'Active')->update(['status' => 'active']);
            DB::table('offices')->where('status', 'Inactive')->update(['status' => 'inactive']);
        });

        DB::statement("ALTER TABLE offices MODIFY status ENUM('active','inactive') NOT NULL DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE offices MODIFY status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active'");
    }
};
