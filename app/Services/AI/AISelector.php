<?php

namespace App\Services\AI;

use App\Services\AI\Strategies\EstrategiaAleatoria;
use App\Services\AI\Strategies\EstrategiaCacador;
use App\Services\AI\Strategies\EstrategiaProbabilistica;

/**
 * Serviço simples que seleciona a estratégia de IA com base na dificuldade do jogo.
 *
 */
class AISelector
{
    /**
     * Executa a jogada da IA e retorna as coordenadas escolhidas.
     *
     * @param mixed $game Objeto que contém `difficulty` e `playerBoard`.
     * @return array ['x' => int, 'y' => int]
     */
    public function jogarTurno($game): array
    {
        $difficulty = $game->difficulty ?? 'basic';
        $strategy = match ($difficulty) {
            'basic' => new EstrategiaAleatoria(),
            'intermediate' => new EstrategiaCacador(),
            'advanced' => new EstrategiaProbabilistica(),
            default => new EstrategiaAleatoria(),
        };

        return $strategy->escolherAlvo($game->playerBoard);
    }
}
