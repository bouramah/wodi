<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('sender_id')->constrained('users');
            $table->foreignId('receiver_id')->constrained('users');
            $table->decimal('amount', 10, 2);
            $table->foreignId('currency_id')->constrained();
            $table->enum('status', ['pending', 'paid', 'cancelled']);
            $table->foreignId('sending_agent_id')->constrained('users');
            $table->foreignId('paying_agent_id')->nullable()->constrained('users');
            $table->date('transfer_date');
            $table->date('payment_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
