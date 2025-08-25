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
        Schema::table('manual_payment_gateways', function (Blueprint $table) {
            $table->string('account_name')->nullable()->after('number');
            $table->string('branch')->nullable()->after('account_name');
            $table->string('routing_no')->nullable()->after('branch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manual_payment_gateways', function (Blueprint $table) {
            $table->dropColumn(['account_name', 'branch', 'routing_no']);
        });
    }
};
