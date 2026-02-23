<?php

namespace App\Services\AI\Strategies;

use App\Services\AI\Contracts\EstrategiaOponente;
use App\Models\Board;

class EstrategiaAleatoria implements EstrategiaOponente
{
    public function escolherAlvo(Board $playerBoard): array
    {
        // Recupera todos os tiros já dados
    $existingShots = $playerBoard->shots()->get(['x', 'y']);

        // Cria um mapa de coordenadas ocupadas "1-2" => true
        $occupied = [];
        foreach ($existingShots as $shot) {
            $occupied["{$shot->x}-{$shot->y}"] = true;
        }

        $available = [];
        // Varre o grid (10x10 = 100 iterações, super rápido)
        for ($x = 0; $x < 10; $x++) {
            for ($y = 0; $y < 10; $y++) {
                if (!isset($occupied["{$x}-{$y}"])) {
                    $available[] = ['x' => $x, 'y' => $y];
                }
            }
        }

        // Se não houver jogadas (fim de jogo), evita erro
        if (empty($available)) {
            throw new \Exception("Não há mais jogadas possíveis.");
        }

        // Retorna uma aleatória da lista de disponíveis
        return $available[array_rand($available)];
    }
}