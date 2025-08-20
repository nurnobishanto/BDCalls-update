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
        Schema::create('number_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ip_number_id')->constrained()->onDelete('cascade');
            $table->string('account_type');
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_country_code')->nullable();
            $table->string('whatsapp_country_code')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('ip_number')->nullable();
            $table->string('enather_ip_number')->nullable();
            $table->string('nid_font_image')->nullable();
            $table->string('nid_back_image')->nullable();
            $table->string('trade_license')->nullable();
            $table->string('selfie_photo')->nullable();
            $table->enum('status', ['pending', 'progress', 'reject', 'resolved'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'canceled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->timestamps();
            $table->softDeletes(); // for soft delete support
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('number_purchases');
    }
};
