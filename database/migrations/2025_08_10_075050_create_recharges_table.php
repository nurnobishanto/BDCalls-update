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
        Schema::create('recharges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('number');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method');
            $table->string('screenshot')->nullable();
            $table->foreignId('minute_bundle_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'in-progress', 'complete', 'reject'])->default('pending');
            $table->enum('payment_status', ['pending', 'cancel', 'failed','paid'])->default('pending');
            $table->json('payment_request')->nullable();
            $table->json('payment_response')->nullable();
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recharges');
    }
};
