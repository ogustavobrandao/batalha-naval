<?php

namespace App\Services;

use App\Models\Medalha;
use App\Models\Partida;
use App\Models\Ranking;
use App\Models\Tabuleiro;

class MedalhaService
{
    /**
     * Avalia e concede medalhas ao jogador ao fim de uma partida.
     *
     * @param int $userId
     * @param Partida $partida
     * @param Ranking $ranking  — registro já salvo no calcularERegistrarRanking
     */
    public function avaliarEConceder(int $userId, Partida $partida, Ranking $ranking): void
    {
        if (!$ranking->venceu) {
            // Medalhas só são concedidas a quem venceu (exceto nenhuma)
            return;
        }

        $this->verificarAlmirante($userId, $partida, $ranking);
        $this->verificarCapitaoMarGuerra($userId, $partida, $ranking);
        $this->verificarCapitao($userId, $partida, $ranking);
        $this->verificarMarinheiro($userId, $partida, $ranking);
    }

    /**
     * Medalha Almirante: vencer sem perder nenhum navio.
     * Verifica se o tabuleiro do próprio jogador não tem nenhuma célula com status 'afundado' ou 'acerto'.
     */
    private function verificarAlmirante(int $userId, Partida $partida, Ranking $ranking): void
    {
        $meuTabuleiro = Tabuleiro::where('partida_id', $partida->id)
            ->where('user_id', $userId)
            ->first();

        if (!$meuTabuleiro) return;

        $levouTiro = false;
        foreach ($meuTabuleiro->tabuleiro_grid as $linha) {
            foreach ($linha as $celula) {
                if (in_array($celula['status'] ?? '', ['acerto', 'afundado'])) {
                    $levouTiro = true;
                    break 2;
                }
            }
        }

        if (!$levouTiro) {
            $this->conceder($userId, $partida->id, 'almirante');
        }
    }

    /**
     * Medalha Capitão de Mar e Guerra: 8 tiros consecutivos acertados.
     * Medalha Capitão: 7 tiros consecutivos acertados.
     * Analisa a sequência ordenada de tiros no tabuleiro do inimigo.
     */
    private function verificarCapitaoMarGuerra(int $userId, Partida $partida, Ranking $ranking): void
    {
        if ($this->maiorSequenciaAcertos($userId, $partida) >= 8) {
            $this->conceder($userId, $partida->id, 'capitao_mar_guerra');
        }
    }

    private function verificarCapitao(int $userId, Partida $partida, Ranking $ranking): void
    {
        if ($this->maiorSequenciaAcertos($userId, $partida) >= 7) {
            $this->conceder($userId, $partida->id, 'capitao');
        }
    }

    /**
     * Retorna o maior número de acertos consecutivos do jogador no tabuleiro inimigo.
     */
    private function maiorSequenciaAcertos(int $userId, Partida $partida): int
    {
        // Tabuleiro onde o jogador disparou = tabuleiro do inimigo
        $queryInimigo = Tabuleiro::where('partida_id', $partida->id);

        if ($partida->eMultiplayer()) {
            $queryInimigo->where('user_id', '!=', $userId);
        } else {
            $queryInimigo->whereNull('user_id');
        }

        $tabuleiroInimigo = $queryInimigo->first();
        if (!$tabuleiroInimigo) return 0;

        // Pega os tiros em ordem cronológica
        $tiros = $tabuleiroInimigo->tiros()
            ->orderBy('id')
            ->get(['foi_atingido']);

        $maior = 0;
        $atual = 0;

        foreach ($tiros as $tiro) {
            if ($tiro->foi_atingido) {
                $atual++;
                $maior = max($maior, $atual);
            } else {
                $atual = 0;
            }
        }

        return $maior;
    }

    /**
     * Medalha Marinheiro: vencer em menos de 3 minutos (180 segundos).
     */
    private function verificarMarinheiro(int $userId, Partida $partida, Ranking $ranking): void
    {
        if ($ranking->tempo_segundos > 0 && $ranking->tempo_segundos <= 180) {
            $this->conceder($userId, $partida->id, 'marinheiro');
        }
    }

    /**
     * Persiste a medalha, ignorando duplicatas (unique constraint na migration).
     */
    private function conceder(int $userId, int $partidaId, string $tipo): void
    {
        Medalha::firstOrCreate([
            'user_id'    => $userId,
            'partida_id' => $partidaId,
            'tipo'       => $tipo,
        ]);
    }
}
