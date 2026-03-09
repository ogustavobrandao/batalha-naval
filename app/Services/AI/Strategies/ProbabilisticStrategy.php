<?php

namespace App\Services\AI\Strategies;

use App\Services\AI\Contracts\OpponentStrategy;
use App\Models\Tabuleiro;


class ProbabilisticStrategy implements OpponentStrategy
{
    protected array $tamanhosNavios = [6, 6, 4, 4, 3, 1];

    public function chooseTarget(Tabuleiro $playerBoard): array
    {
        $mapaDeCalor = array_fill(0, 10, array_fill(0, 10, 0));
        
        $todosOsTiros = $playerBoard->tiros()->get();
        $memoriaTiros = [];
        foreach ($todosOsTiros as $t) {
            $memoriaTiros[$t->x][$t->y] = $t;
        }

        $naviosRestantes = $this->tamanhosNavios;

        // Passa o array de memória para as funções não baterem no banco
        foreach ($naviosRestantes as $tamanho) {
            $this->calcularProbabilidade($mapaDeCalor, $tamanho, $memoriaTiros);
        }

        $this->aplicarPesoDeCaca($mapaDeCalor, $memoriaTiros);

        $melhorAlvo = ['x' => 0, 'y' => 0, 'score' => -1];

        for ($x = 0; $x < 10; $x++) {
            for ($y = 0; $y < 10; $y++) {
                // Checa no array se já atirou
                if (!isset($memoriaTiros[$x][$y])) {
                    if ($mapaDeCalor[$x][$y] > $melhorAlvo['score']) {
                        $melhorAlvo = [
                            'x' => $x, 
                            'y' => $y, 
                            'score' => $mapaDeCalor[$x][$y]
                        ];
                    }
                }
            }
        }

        if ($melhorAlvo['score'] === -1) {
            return (new RandomStrategy())->chooseTarget($playerBoard);
        }

        return ['x' => $melhorAlvo['x'], 'y' => $melhorAlvo['y']];
    }

    private function calcularProbabilidade(array &$mapa, int $tamanho, array $memoria): void
    {
        for ($x = 0; $x < 10; $x++) {
            for ($y = 0; $y < 10; $y++) {
                if ($this->podeEncaixar($x, $y, $tamanho, 'H', $memoria)) {
                    for ($i = 0; $i < $tamanho; $i++) {
                        $mapa[$x + $i][$y]++;
                    }
                }
                if ($tamanho > 1 && $this->podeEncaixar($x, $y, $tamanho, 'V', $memoria)) {
                    for ($i = 0; $i < $tamanho; $i++) {
                        $mapa[$x][$y + $i]++;
                    }
                }
            }
        }
    }

    private function podeEncaixar(int $x, int $y, int $tamanho, string $orientacao, array $memoria): bool
    {
        if ($orientacao === 'H') {
            if ($x + $tamanho > 10) return false;
            for ($i = 0; $i < $tamanho; $i++) {
                if ($this->foiAguaOuAfundadoMemoria($x + $i, $y, $memoria)) return false; 
            }
        } else {
            if ($y + $tamanho > 10) return false;
            for ($i = 0; $i < $tamanho; $i++) {
                if ($this->foiAguaOuAfundadoMemoria($x, $y + $i, $memoria)) return false;
            }
        }
        return true;
    }

    // Função local para ler da memória (super rápido)
    private function foiAguaOuAfundadoMemoria(int $x, int $y, array $memoria): bool
    {
        if (!isset($memoria[$x][$y])) return false;
        
        $tiro = $memoria[$x][$y];
        return !$tiro->foi_atingido || $tiro->navio_afundado;
    }

    private function aplicarPesoDeCaca(array &$mapa, array $memoria): void
    {
        // 1. Coleta todos os acertos pendentes (navios atingidos mas não afundados)
        $acertosPendentes = [];
        foreach ($memoria as $linha) {
            foreach ($linha as $tiro) {
                if ($tiro->foi_atingido && !$tiro->navio_afundado) {
                    $acertosPendentes[] = $tiro;
                }
            }
        }

        if (empty($acertosPendentes)) {
            return;
        }

        // 2. Tenta encontrar alinhamentos entre os acertos para focar nas extremidades
        $temAlinhamento = false;

        foreach ($acertosPendentes as $tiroA) {
            foreach ($acertosPendentes as $tiroB) {
                if ($tiroA->id === $tiroB->id) continue;

                // Verifica alinhamento Horizontal
                if ($tiroA->x === $tiroB->x && abs($tiroA->y - $tiroB->y) === 1) {
                    $temAlinhamento = true;
                    // Bônus nas extremidades horizontais
                    $this->adicionarPesoSeValido($mapa, $memoria, $tiroA->x, min($tiroA->y, $tiroB->y) - 1, 100);
                    $this->adicionarPesoSeValido($mapa, $memoria, $tiroA->x, max($tiroA->y, $tiroB->y) + 1, 100);
                }

                // Verifica alinhamento Vertical
                if ($tiroA->y === $tiroB->y && abs($tiroA->x - $tiroB->x) === 1) {
                    $temAlinhamento = true;
                    // Bônus nas extremidades verticais
                    $this->adicionarPesoSeValido($mapa, $memoria, min($tiroA->x, $tiroB->x) - 1, $tiroA->y, 100);
                    $this->adicionarPesoSeValido($mapa, $memoria, max($tiroA->x, $tiroB->x) + 1, $tiroA->y, 100);
                }
            }
        }

        // 3. Se não achou alinhamento (apenas 1 acerto ou acertos distantes), espalha peso nos vizinhos normais
        if (!$temAlinhamento) {
            foreach ($acertosPendentes as $tiro) {
                $vizinhos = [
                    ['x' => $tiro->x - 1, 'y' => $tiro->y],
                    ['x' => $tiro->x + 1, 'y' => $tiro->y],
                    ['x' => $tiro->x, 'y' => $tiro->y - 1],
                    ['x' => $tiro->x, 'y' => $tiro->y + 1],
                ];

                foreach ($vizinhos as $v) {
                    $this->adicionarPesoSeValido($mapa, $memoria, $v['x'], $v['y'], 50);
                }
            }
        }
    }

    private function adicionarPesoSeValido(array &$mapa, array $memoria, int $x, int $y, int $peso): void
    {
        if ($x >= 0 && $x < 10 && $y >= 0 && $y < 10) {
            if (!isset($memoria[$x][$y])) { // Só adiciona peso se ainda não atirou aqui
                $mapa[$x][$y] += $peso;
            }
        }
    }
}
