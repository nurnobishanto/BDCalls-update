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
        Schema::create('minute_bundle_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('minute_bundle_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_ip_number_id');
            $table->decimal('price', 10, 2);
            $table->enum('status', ['pending','progress', 'reject', 'resolved'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'canceled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Optional: add foreign keys if you have the other tables
            $table->foreign('minute_bundle_id')->references('id')->on('minute_bundles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_ip_number_id')->references('id')->on('user_ip_numbers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minute_bundle_purchases');
    }
};
