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
        Schema::create('due_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_ip_number_id')->constrained()->cascadeOnDelete();
            $table->decimal('call_rate', 10, 2);
            $table->integer('minutes');
            $table->decimal('service_charge', 10, 2);
            $table->string('month'); // e.g., '2025-08'
            $table->decimal('total', 10, 2);
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->timestamps();
            $table->softDeletes();

            // Unique combination of month + user_ip_number_id
            $table->unique(['month', 'user_ip_number_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('due_bills');
    }
};
