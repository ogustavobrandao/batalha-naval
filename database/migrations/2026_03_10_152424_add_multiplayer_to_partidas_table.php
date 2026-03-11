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
        Schema::table('partidas', function (Blueprint $table) {
            // jogador2_id para o oponente humano
            $table->foreignId('jogador2_id')->nullable()->constrained('users')->onDelete('cascade');

            // Controle de quem deve jogar agora (ID do User)
            $table->foreignId('turno_atual_id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partidas', function (Blueprint $table) {
            $table->dropForeign(['jogador2_id']);
            $table->dropColumn('jogador2_id');
            $table->dropForeign(['turno_atual_id']);
            $table->dropColumn('turno_atual_id');
        });
    }
};
