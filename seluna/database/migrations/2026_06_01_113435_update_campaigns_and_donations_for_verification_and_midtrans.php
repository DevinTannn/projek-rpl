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
        // 1. Update Campaigns Table
        Schema::table('campaigns', function (Blueprint $table) {
            // Modify status column to include 'pending' and 'rejected'
            // Since it's an enum, we might need to recreate it or use raw SQL depending on DB driver
            // For better compatibility (MySQL), we use raw SQL to update enum
        });
        
        DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM('draft', 'pending', 'active', 'closed', 'rejected') NOT NULL DEFAULT 'pending'");

        // 2. Update Donations Table
        Schema::table('donations', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
            $table->string('snap_token')->nullable()->after('status');
            $table->string('external_id')->nullable()->after('snap_token'); // for reference
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Restore original enum if needed, but usually we just leave it or revert
        });
        
        DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM('draft', 'active', 'closed') NOT NULL DEFAULT 'draft'");

        Schema::table('donations', function (Blueprint $table) {
            $table->string('status')->default('success')->change();
            $table->dropColumn(['snap_token', 'external_id']);
        });
    }
};
