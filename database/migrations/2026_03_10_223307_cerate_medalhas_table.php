<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medalhas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('partida_id')->constrained()->cascadeOnDelete();
            $table->enum('tipo', ['almirante', 'capitao_mar_guerra', 'capitao', 'marinheiro']);
            $table->timestamps();

            // Evita duplicata: mesmo jogador não ganha a mesma medalha na mesma partida
            $table->unique(['user_id', 'partida_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medalhas');
    }
};
