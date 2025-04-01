<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temporary_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('depositor_id')->constrained('users');
            $table->decimal('amount', 10, 2);
            $table->decimal('remaining_amount', 10, 2);
            $table->foreignId('agent_id')->constrained('users');
            $table->date('deposit_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temporary_deposits');
    }
};
