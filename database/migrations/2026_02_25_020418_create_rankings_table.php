// database/migrations/2026_02_24_create_rankings_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('partida_id')->constrained()->cascadeOnDelete();
            $table->boolean('venceu')->default(false);
            $table->enum('dificuldade', ['facil', 'medio', 'dificil']);
            $table->unsignedSmallInteger('tiros_dados')->default(0);
            $table->unsignedSmallInteger('acertos')->default(0);
            $table->unsignedSmallInteger('navios_afundados')->default(0);
            $table->unsignedInteger('tempo_segundos')->default(0);
            $table->unsignedInteger('pontuacao')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rankings');
    }
};
