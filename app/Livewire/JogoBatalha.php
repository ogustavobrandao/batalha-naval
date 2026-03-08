<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Partida;
use App\Models\Tabuleiro;
use App\Services\AI\AISelector;
use Illuminate\Support\Facades\Auth;

class JogoBatalha extends Component
{
    public $partida;
    public array $meuTabuleiro = [];
    public array $tabuleiroOponente = [];
    public string $fase = 'posicionamento';
    public $navioSelecionado = null;

    // Controles de Visão e Direção
    public int $direcaoNavio = 0; // 0: Leste, 1: Sul, 2: Oeste, 3: Norte
    public int $anguloRadar = -45;
    public array $statusFrota = [];

    // Configuração da frota (Baseado no PDF do projeto)
    public array $naviosDisponiveis = [
        'porta_avioes' => ['nome' => 'Porta-aviões', 'tamanho' => 6, 'qtd' => 2, 'cor' => '#6366f1'],
        'navio_guerra' => ['nome' => 'Navio de guerra', 'tamanho' => 4, 'qtd' => 2, 'cor' => '#0ea5e9'],
        'encouracado'  => ['nome' => 'Encouraçado', 'tamanho' => 3, 'qtd' => 1, 'cor' => '#f59e0b'],
        'submarino'    => ['nome' => 'Submarino', 'tamanho' => 1, 'qtd' => 1, 'cor' => '#10b981'],
    ];

    public function mount($id)
    {
        $this->partida = Partida::findOrFail($id);
        $this->carregarTabuleiros();
        $this->sincronizarStatusFrota();
    }

    private function carregarTabuleiros()
    {
        $meuBoard = Tabuleiro::where('partida_id', $this->partida->id)
            ->where('user_id', Auth::id())
            ->first();
        $this->meuTabuleiro = $meuBoard->tabuleiro_grid;

        $boardOponente = Tabuleiro::where('partida_id', $this->partida->id)
            ->whereNull('user_id')
            ->first();
        $this->tabuleiroOponente = $boardOponente->tabuleiro_grid;

        $this->fase = ($this->partida->status === 'posicionamento') ? 'posicionamento' : 'batalha';
    }

    public function sincronizarStatusFrota()
    {
        $contagem = [];
        foreach ($this->meuTabuleiro as $linha) {
            foreach ($linha as $celula) {
                if (isset($celula['ship_id'])) {
                    $contagem[$celula['ship_id']] = $celula['navio'];
                }
            }
        }

        foreach ($this->naviosDisponiveis as $tipo => $config) {
            $jaPosicionados = count(array_unique(array_keys(array_filter($contagem, fn($t) => $t === $tipo))));
            $this->statusFrota[$tipo] = $config['qtd'] - $jaPosicionados;
        }
    }

    // --- INTERAÇÃO ---

    public function girarRadar()
    {
        $this->anguloRadar += 90;
    }

    public function girarNavio()
    {
        $this->direcaoNavio = ($this->direcaoNavio + 1) % 4;
    }

    public function selecionarNavio($tipo)
    {
        if ($this->statusFrota[$tipo] > 0) {
            $this->navioSelecionado = $tipo;
        }
    }

    public function clicarCelula($r, $c)
    {
        if ($this->fase === 'posicionamento') {
            $this->processarPosicionamento($r, $c);
        } else {
            $this->atirar($r, $c);
        }
    }

    // --- POSICIONAMENTO E REMOÇÃO ---

    private function processarPosicionamento($r, $c)
    {
        // Se clicar em um navio já existente, remove-o
        if (isset($this->meuTabuleiro[$r][$c]['ship_id'])) {
            $this->removerNavio($this->meuTabuleiro[$r][$c]['ship_id']);
            return;
        }

        if (!$this->navioSelecionado) return;

        $tamanho = $this->naviosDisponiveis[$this->navioSelecionado]['tamanho'];
        $idNavio = uniqid($this->navioSelecionado . '_');
        $coordenadas = [];

        // Calcula ocupação baseada na direção
        for ($i = 0; $i < $tamanho; $i++) {
            $linhaAtual = $r;
            $colunaAtual = $c;

            if ($this->direcaoNavio === 0) $colunaAtual += $i;      // Leste
            elseif ($this->direcaoNavio === 1) $linhaAtual += $i;  // Sul
            elseif ($this->direcaoNavio === 2) $colunaAtual -= $i; // Oeste
            elseif ($this->direcaoNavio === 3) $linhaAtual -= $i;  // Norte

            // Validação de limites e colisão
            if ($linhaAtual < 0 || $linhaAtual > 9 || $colunaAtual < 0 || $colunaAtual > 9 || isset($this->meuTabuleiro[$linhaAtual][$colunaAtual]['navio'])) {
                return;
            }
            $coordenadas[] = [$linhaAtual, $colunaAtual];
        }

        // Aplica o posicionamento
        foreach ($coordenadas as $coord) {
            $this->meuTabuleiro[$coord[0]][$coord[1]] = [
                'status' => 'posicionado',
                'navio' => $this->navioSelecionado,
                'ship_id' => $idNavio,
                'cor' => $this->naviosDisponiveis[$this->navioSelecionado]['cor']
            ];
        }

        $this->navioSelecionado = null;
        $this->salvarMeuTabuleiro();
        $this->sincronizarStatusFrota();
    }

    public function removerNavio($idNavio)
    {
        foreach ($this->meuTabuleiro as $r => $linha) {
            foreach ($linha as $c => $celula) {
                if (($celula['ship_id'] ?? null) === $idNavio) {
                    $this->meuTabuleiro[$r][$c] = ['status' => 'agua', 'navio' => null];
                }
            }
        }
        $this->salvarMeuTabuleiro();
        $this->sincronizarStatusFrota();
    }

    // --- COMBATE ---

    public function atirar($r, $c)
    {
        if ($this->fase !== 'batalha') return;

        $tabuleiroIA = Tabuleiro::where('partida_id', $this->partida->id)->whereNull('user_id')->first();

        // Impede atirar no mesmo lugar duas vezes
        if ($this->tabuleiroOponente[$r][$c]['status'] !== 'agua') return;

        $acertou = $this->registrarTiro($tabuleiroIA, $r, $c);

        // Se errou, passa o turno para a IA
        if (!$acertou) {
            $this->turnoIA();
        }
    }

    private function turnoIA()
    {
        $tabuleiroJogador = Tabuleiro::where('partida_id', $this->partida->id)
            ->where('user_id', Auth::id())
            ->first();

        $dadosJogo = (object)[
            'difficulty' => $this->partida->dificuldade,
            'playerBoard' => $tabuleiroJogador
        ];

        $alvo = (new AISelector())->playTurn($dadosJogo);
        $acertou = $this->registrarTiro($tabuleiroJogador, $alvo['x'], $alvo['y']);

        // Se a IA acertar, ela joga novamente
        if ($acertou && $this->fase === 'batalha') {
            $this->turnoIA();
        }
    }

    private function registrarTiro($board, $x, $y)
    {
        $grid = $board->tabuleiro_grid;
        $idNavio = $grid[$x][$y]['ship_id'] ?? null;
        $foiAcerto = (bool)$idNavio;

        $tiro = $board->tiros()->create(['x' => $x, 'y' => $y, 'foi_atingido' => $foiAcerto]);

        if ($foiAcerto) {
            $grid[$x][$y]['status'] = 'acerto';
            if ($this->verificarSeAfundou($grid, $idNavio)) {
                $tiro->update(['navio_afundado' => true]);
                $this->marcarComoAfundado($grid, $idNavio);
            }
        } else {
            $grid[$x][$y]['status'] = 'erro';
        }

        $board->update(['tabuleiro_grid' => $grid]);
        $this->carregarTabuleiros();
        $this->verificarVitoria();

        return $foiAcerto;
    }

    private function verificarSeAfundou($tabuleiro_grid, $id)
    {
        foreach ($tabuleiro_grid as $linha) {
            foreach ($linha as $c) {
                if (($c['ship_id'] ?? '') === $id && $c['status'] !== 'acerto') {
                    return false;
                }
            }
        }
        return true;
    }

    private function marcarComoAfundado(&$tabuleiro_grid, $id)
    {
        foreach ($tabuleiro_grid as $r => $linha) {
            foreach ($linha as $c => $celula) {
                if (($celula['ship_id'] ?? '') === $id) {
                    $tabuleiro_grid[$r][$c]['status'] = 'afundado';
                }
            }
        }
    }

    private function verificarVitoria()
    {
        $jogadorVenceu = $this->todosAfundados($this->tabuleiroOponente);
        $iaVenceu      = $this->todosAfundados($this->meuTabuleiro);

        if ($jogadorVenceu || $iaVenceu) {
            $this->partida->update([
                'status'      => 'finalizada',
                'finished_at' => now(),
            ]);
            $this->calcularERegistrarRanking($jogadorVenceu);
            $this->fase = 'finalizada';
        }
    }

    private function todosAfundados($tabuleiro_grid)
    {
        foreach ($tabuleiro_grid as $linha) {
            foreach ($linha as $celula) {
                if (isset($celula['ship_id']) && $celula['status'] !== 'afundado') {
                    return false;
                }
            }
        }
        return true;
    }

    public function iniciarBatalha()
    {
        if (array_sum($this->statusFrota) === 0) {
            $this->posicionarIA();
            $this->partida->update(['status' => 'em_andamento']);
            $this->fase = 'batalha';
            $this->carregarTabuleiros();
        }
    }

    private function posicionarIA()
    {
        $tabuleiroIA = Tabuleiro::where('partida_id', $this->partida->id)->whereNull('user_id')->first();
        $grid = $tabuleiroIA->tabuleiro_grid;

        foreach ($this->naviosDisponiveis as $tipo => $config) {
            for ($q = 0; $q < $config['qtd']; $q++) {
                $posicionado = false;
                while (!$posicionado) {
                    $r = rand(0, 9);
                    $c = rand(0, 9);
                    $dir = rand(0, 3);

                    if ($this->validarPosicaoIA($grid, $r, $c, $config['tamanho'], $dir)) {
                        $idNavio = uniqid($tipo.'_');
                        for ($i = 0; $i < $config['tamanho']; $i++) {
                            $nr = $r; $nc = $c;
                            if($dir === 0) $nc += $i;
                            elseif($dir === 1) $nr += $i;
                            elseif($dir === 2) $nc -= $i;
                            else $nr -= $i;

                            $grid[$nr][$nc] = [
                                'status' => 'agua',
                                'navio' => $tipo,
                                'ship_id' => $idNavio,
                                'cor' => $config['cor']
                            ];
                        }
                        $posicionado = true;
                    }
                }
            }
        }
        $tabuleiroIA->update(['tabuleiro_grid' => $grid]);
    }

    private function validarPosicaoIA($grid, $r, $c, $tam, $dir)
    {
        for ($i = 0; $i < $tam; $i++) {
            $nr = $r; $nc = $c;
            if($dir === 0) $nc += $i;
            elseif($dir === 1) $nr += $i;
            elseif($dir === 2) $nc -= $i;
            else $nr -= $i;

            if($nr < 0 || $nr > 9 || $nc < 0 || $nc > 9 || isset($grid[$nr][$nc]['ship_id'])) {
                return false;
            }
        }
        return true;
    }

    private function salvarMeuTabuleiro()
    {
        Tabuleiro::where('partida_id', $this->partida->id)
            ->where('user_id', Auth::id())
            ->update(['tabuleiro_grid' => $this->meuTabuleiro]);
    }

    public function render()
    {
        return view('livewire.tabuleiro');
    }

    private function calcularERegistrarRanking(bool $venceu): void
    {
        $tabuleiroIA      = Tabuleiro::where('partida_id', $this->partida->id)->whereNull('user_id')->first();
        $tabuleiroJogador = Tabuleiro::where('partida_id', $this->partida->id)->where('user_id', Auth::id())->first();

        // Tiros que o JOGADOR deu (no tabuleiro da IA)
        $tirosDados    = $tabuleiroIA->tiros()->count();
        $acertos       = $tabuleiroIA->tiros()->where('foi_atingido', true)->count();
        $naviosAfundados = $tabuleiroIA->tiros()->where('navio_afundado', true)->count();

        // Tempo em segundos
        $tempo = $this->partida->started_at
            ? (int) $this->partida->started_at->diffInSeconds(now())
            : 0;

        // Cálculo de pontuação
        $base = match($this->partida->dificuldade) {
            'facil'  => 100,
            'medio'  => 250,
            'dificil'=> 500,
            default  => 100,
        };

        $bonusPrecisao = $tirosDados > 0
            ? (int) round(($acertos / $tirosDados) * 200)
            : 0;

        // Bônus de tempo: máx 300 pts, decresce com o tempo (1 pt perdido a cada 10s)
        $bonusTempo = max(0, 300 - (int) floor($tempo / 10));

        $bonusVitoria = $venceu ? 300 : 0;

        $pontuacao = $base + $bonusPrecisao + $bonusTempo + $bonusVitoria;

        \App\Models\Ranking::create([
            'user_id'         => Auth::id(),
            'partida_id'      => $this->partida->id,
            'venceu'          => $venceu,
            'dificuldade'     => $this->partida->dificuldade,
            'tiros_dados'     => $tirosDados,
            'acertos'         => $acertos,
            'navios_afundados'=> $naviosAfundados,
            'tempo_segundos'  => $tempo,
            'pontuacao'       => $pontuacao,
        ]);
    }
}
