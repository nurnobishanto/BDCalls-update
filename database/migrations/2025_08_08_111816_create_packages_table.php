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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('registration_number')->nullable();
            $table->string('user')->nullable();
            $table->string('call_channel')->nullable();
            $table->string('call_channel_charges')->nullable();
            $table->string('additional_extensions')->nullable();
            $table->string('ivr_support')->nullable();
            $table->string('web_space')->nullable();
            $table->string('ram')->nullable();
            $table->string('call_record')->nullable();
            $table->string('voice_mail')->nullable();
            $table->string('call_forward')->nullable();
            $table->string('call_transfer')->nullable();
            $table->string('data_backup')->nullable();
            $table->string('recovery')->nullable();
            $table->string('ring_group')->nullable();
            $table->string('amber_blacklist')->nullable();
            $table->string('call_charge_mobile_tnt')->nullable();
            $table->string('pulse')->nullable();
            $table->string('call_charges_ivr_number')->nullable();
            $table->string('call_charges_own_network')->nullable();
            $table->string('incoming_charges')->nullable();
            $table->string('supported_devices')->nullable();
            $table->string('spam_filter')->nullable();
            $table->string('connection_type')->nullable();
            $table->string('connection_method')->nullable();
            $table->string('custom_configuration')->nullable();
            $table->string('connection_charges')->nullable();
            $table->string('uptime_guarantee')->nullable();
            $table->string('control_panel')->nullable();
            $table->string('account_will_remain_day')->nullable();
            $table->string('automatic_termination_day')->nullable();

            $table->decimal('call_rate', 10, 2)->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('status')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
