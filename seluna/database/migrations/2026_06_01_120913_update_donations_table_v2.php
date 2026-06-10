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
        Schema::table('donations', function (Blueprint $table) {
            if (!Schema::hasColumn('donations', 'order_id')) {
                $table->string('order_id')->unique()->after('campaign_id');
            }
            if (!Schema::hasColumn('donations', 'payment_url')) {
                $table->string('payment_url')->nullable()->after('snap_token');
            }
            if (!Schema::hasColumn('donations', 'midtrans_status')) {
                $table->string('midtrans_status')->nullable()->after('status');
            }
            if (!Schema::hasColumn('donations', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()->constrained('users')->after('midtrans_status');
            }
            if (!Schema::hasColumn('donations', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['order_id', 'payment_url', 'midtrans_status', 'verified_by', 'verified_at']);
        });
    }
};
