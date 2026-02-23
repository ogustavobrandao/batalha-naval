<?php

namespace App\Services\AI\Contracts;

use App\Models\Board;

interface EstrategiaOponente
{
    // Recebe o tabuleiro do jogador humano para decidir onde atirar
    public function escolherAlvo(Board $playerBoard): array; // Retorna ['x' => 1, 'y' => 2]
}