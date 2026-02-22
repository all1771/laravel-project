<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone', 20);
            $table->unsignedTinyInteger('hours'); // 1, 2, 3, 4
            $table->boolean('need_ticket')->default(false);
            $table->foreignId('skate_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('skate_size_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('amount'); // итого в рублях
            $table->string('yookassa_payment_id')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
