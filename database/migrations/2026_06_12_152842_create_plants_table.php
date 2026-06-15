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
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->string('nome_popular');
            $table->string('nome_cientifico')->unique();
            $table->string('familia')->nullable();
            $table->string('genero')->nullable();
            $table->string('especie')->nullable();
            $table->string('origem')->nullable();
            $table->enum('habitat_luz', ['sol_pleno', 'meia_sombra', 'sombra'])->default('meia_sombra');
            $table->integer('porte_max_cm')->nullable();
            $table->boolean('toxica_pets')->default(false);
            $table->json('epoca_poda')->nullable();
            $table->longText('beneficios')->nullable();
            $table->longText('maleficios')->nullable();
            $table->longText('curiosidades')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();

            $table->index('nome_popular');
            $table->index('habitat_luz');
            $table->index('toxica_pets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
