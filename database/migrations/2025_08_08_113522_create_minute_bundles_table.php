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
        Schema::create('minute_bundles', function (Blueprint $table) {
            $table->id();

            $table->string('title'); // eg: ব্যান্ডেল- ১ (3000 মিনিট)
            $table->string('incoming_charge')->nullable(); // ফ্রি
            $table->string('ip_number_charge')->nullable(); // ফ্রি
            $table->string('extension_charge')->nullable(); // ফ্রি
            $table->string('outgoing_call_charge')->nullable(); // eg: ৩৩ পয়সা
            $table->string('pulse')->nullable(); // eg: সেকেন্ড
            $table->integer('minutes')->default(0); // eg: 3000
            $table->string('validity')->nullable(); // eg: 30 days
            $table->decimal('price', 10, 2)->default(0); // eg: 1350

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
        Schema::dropIfExists('minute_bundles');
    }
};
