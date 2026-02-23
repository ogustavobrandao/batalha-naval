<?php

namespace App\Services\AI\Strategies;

use App\Services\AI\Contracts\EstrategiaOponente;
use App\Models\Board;

class EstrategiaCacador implements EstrategiaOponente
{
    public function escolherAlvo(Board $playerBoard): array
    {
        // BUSCA INTELIGENTE:
        // Pega o último tiro que foi um ACERTO (is_hit),
        // mas que NÃO resultou no afundamento total do navio (ship_sunk false).
        // Isso garante que, mesmo se a IA errar o tiro seguinte, ela lembre de voltar para terminar o serviço.
        $targetShot = $playerBoard->shots()
            ->where('is_hit', true)
            ->where('ship_sunk', false) // Assumindo que você atualiza isso no DB ao afundar
            ->latest()
            ->first();
        
        if ($targetShot) {
            // Tenta achar um vizinho desse acerto específico
            $neighbor = $this->getValidNeighbor((int)$targetShot->x, (int)$targetShot->y, $playerBoard);
            
            // Se achou vizinho, atira nele.
            // Se NÃO achou (ex: cercado de erros mas navio não afundou - caso raro, mas possível),
            // volta para Random ou tenta outra estratégia de fallback.
            if ($neighbor) {
                return $neighbor;
            }
        }

        // Se não há navios feridos pendentes, modo Aleatório
        return (new EstrategiaAleatoria())->escolherAlvo($playerBoard);
    }
    
    /**
     * Retorna um vizinho válido (não fora do tabuleiro e ainda não atacado).
     * Se nenhum vizinho válido for encontrado, delega para RandomStrategy.
     */
    protected function getValidNeighbor(int $x, int $y, Board $playerBoard): array
    {
        $neighbors = [
            ['x' => $x, 'y' => $y - 1], // norte
            ['x' => $x, 'y' => $y + 1], // sul
            ['x' => $x + 1, 'y' => $y], // leste
            ['x' => $x - 1, 'y' => $y], // oeste
        ];

        // Embaralha para não seguir sempre a mesma ordem
        shuffle($neighbors);

        foreach ($neighbors as $n) {
            if ($n['x'] < 0 || $n['x'] > 9 || $n['y'] < 0 || $n['y'] > 9) {
                continue;
            }
            if (!$playerBoard->hasShotAt($n['x'], $n['y'])) {
                return $n;
            }
        }

        // Fallback para aleatório caso não haja vizinhos válidos
        return (new EstrategiaAleatoria())->escolherAlvo($playerBoard);
    }
}
