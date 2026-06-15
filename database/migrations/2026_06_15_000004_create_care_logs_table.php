<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('care_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('plant_id')->constrained('plants')->onDelete('cascade');
            $table->string('tipo', 20); // rega | adubacao | poda
            $table->date('data');
            $table->timestamps();

            $table->index(['user_id', 'plant_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('care_logs');
    }
};
