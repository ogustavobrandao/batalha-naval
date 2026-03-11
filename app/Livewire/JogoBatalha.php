<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Partida;
use App\Models\Tabuleiro;
use \App\Models\Ranking;
use App\Services\AI\AISelector;
use Illuminate\Support\Facades\Auth;
use App\Events\JogadaRealizada;
use App\Models\Medalha;
use App\Services\MedalhaService;

class JogoBatalha extends Component
{
    public $partida;
    public array $meuTabuleiro = [];
    public array $tabuleiroOponente = [];
    public string $fase = 'posicionamento';
    public $navioSelecionado = null;

    public bool $jaMoveuNesteTurno = false;
    public $navioParaMover = null; // ID do navio selecionado para movimento
    public bool $bloquearAcoes = false;

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

        if ($this->fase === 'batalha') {
            $this->bloquearAcoes = ($this->partida->turno_atual_id !== Auth::id());
        } else {
            // Se eu já confirmei mas o oponente não, fico em modo de espera (loading)
            $souJogador1 = Auth::id() === $this->partida->criado_por;
            $estouPronto = $souJogador1 ? ($this->partida->jogador1_pronto ?? false) : ($this->partida->jogador2_pronto ?? false);

            if ($estouPronto) {
                $this->bloquearAcoes = true;
            }
        }
    }

    protected function getListeners()
    {
        return [
            "echo-private:partida.{$this->partida->id},.JogadaRealizada" => 'receberJogadaOnline',
        ];
    }

    private function carregarTabuleiros()
    {
        // Carrega o meu tabuleiro
        $meuBoard = Tabuleiro::where('partida_id', $this->partida->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($meuBoard) {
            $this->meuTabuleiro = $meuBoard->tabuleiro_grid;
        } else {
            // Caso de segurança: inicializa grid vazio se não encontrar
            $this->meuTabuleiro = array_fill(0, 10, array_fill(0, 10, ['status' => 'agua', 'navio' => null]));
        }

        // Carrega o tabuleiro do oponente
        $queryOponente = Tabuleiro::where('partida_id', $this->partida->id);

        if ($this->partida->eMultiplayer()) {
            $queryOponente->where('user_id', '!=', Auth::id());
        } else {
            $queryOponente->whereNull('user_id'); // Contra IA
        }

        $boardOponente = $queryOponente->first();

        // Se o oponente ainda não existe (Lobby PvP), carrega um grid vazio temporário
        if ($boardOponente) {
            $this->tabuleiroOponente = $boardOponente->tabuleiro_grid;
        } else {
            $this->tabuleiroOponente = array_fill(0, 10, array_fill(0, 10, ['status' => 'agua', 'navio' => null]));
        }

        $this->fase = match ($this->partida->status) {
            'finalizada' => 'finalizada',
            'em_andamento' => 'batalha',
            default => 'posicionamento'
        };

        // Se sou o criador e ainda não tem oponente, eu posso posicionar,
        // mas não posso atirar (bloquearAcoes impede o tiro, mas não o posicionamento no seu código atual)
        if ($this->partida->modo === 'pvp' && !$this->partida->jogador2_id) {
            // Não bloqueamos aqui para o criador conseguir posicionar os navios dele!
            $this->bloquearAcoes = false;
        }
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
        foreach ($coordenadas as $index => $coord) {
            $this->meuTabuleiro[$coord[0]][$coord[1]] = [
                'status'  => 'posicionado',
                'navio'   => $this->navioSelecionado,
                'ship_id' => $idNavio,
                'cor'     => $this->naviosDisponiveis[$this->navioSelecionado]['cor'],
                'parte'   => $index,
                'tamanho' => $tamanho,
                'direcao' => $this->direcaoNavio,
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
        if ($this->fase !== 'batalha' || $this->bloquearAcoes) return;

        // Permitir tiro se o status for 'agua' OU 'posicionado' (navio escondido)
        // Impedimos apenas se já tiver um tiro no local ('acerto' ou 'erro')
        $statusAlvo = $this->tabuleiroOponente[$r][$c]['status'];
        if ($statusAlvo === 'acerto' || $statusAlvo === 'erro' || $statusAlvo === 'afundado') {
            return;
        }

        $queryInimigo = Tabuleiro::where('partida_id', $this->partida->id);
        if ($this->partida->eMultiplayer()) {
            $queryInimigo->where('user_id', '!=', Auth::id());
        } else {
            $queryInimigo->whereNull('user_id');
        }
        $tabuleiroInimigo = $queryInimigo->first();

        // Registar o tiro
        $acertou = $this->registrarTiro($tabuleiroInimigo, $r, $c);

        if ($this->partida->eMultiplayer()) {
            broadcast(new JogadaRealizada($this->partida->id, 'tiro', ['r' => $r, 'c' => $c]))->toOthers();
            if (!$acertou) {
                $this->passarTurnoOnline();
            }
        } else {
            if (!$acertou) {
                $this->bloquearAcoes = true;
                $this->dispatch('ia-turno');
            }
        }
    }

    private function passarTurnoOnline()
    {
        // O próximo turno é o ID do jogador que nao é o dono do turno atual
        $proximoTurnoId = (Auth::id() === $this->partida->criado_por)
            ? $this->partida->jogador2_id
            : $this->partida->criado_por;

        $this->partida->update(['turno_atual_id' => $proximoTurnoId]);
        $this->bloquearAcoes = true;
    }

    public function receberJogadaOnline($event)
    {
        // 1. Força a atualização dos dados da partida vindos do banco
        $this->partida->refresh();

        if ($this->partida->status === 'finalizada') {
            $this->carregarTabuleiros();
            // Se eu perdi, calcula o ranking de derrota para mim também
            $perdi = $this->todosAfundados($this->meuTabuleiro);
            $this->calcularERegistrarRanking(!$perdi);
            $this->fase = 'finalizada';
            return;
        }

        if (($event['tipo'] ?? '') === 'ready') {
            if ($this->partida->jogador1_pronto && $this->partida->jogador2_pronto) {
                $this->partida->update(['status' => 'em_andamento']);
                $this->fase = 'batalha';
                $this->carregarTabuleiros();
                $this->bloquearAcoes = ($this->partida->turno_atual_id !== Auth::id());
            }
        } else {
            // 2. Recarrega os tabuleiros para mostrar o tiro do oponente
            $this->carregarTabuleiros();

            // 3. Atualiza o estado de bloqueio baseado no novo turno
            $this->bloquearAcoes = ($this->partida->turno_atual_id !== Auth::id());
        }

        // 4. Notifica o Livewire para re-renderizar a página imediatamente
        $this->dispatch('$refresh');
    }

    public function turnoIA()
    {
        if (!$this->bloquearAcoes) return;

        // Se for modo dinâmico, a IA tenta mover um navio antes de atirar
        if ($this->partida->modo === 'dinamico') {
            $this->executarMovimentoIA();
        }

        $tabuleiroJogador = Tabuleiro::where('partida_id', $this->partida->id)
            ->where('user_id', Auth::id())
            ->first();

        $dadosJogo = (object)[
            'difficulty' => $this->partida->dificuldade,
            'playerBoard' => $tabuleiroJogador
        ];

        $alvo = (new AISelector())->playTurn($dadosJogo);
        $acertou = $this->registrarTiro($tabuleiroJogador, $alvo['x'], $alvo['y']);

        // Se a IA acertar e o jogo continuar em batalha, a IA joga novamente após um tempo
        if ($acertou && $this->fase === 'batalha') {
            $this->dispatch('ia-turno');
        } else {
            // Se errou (ou acabou o jogo), a vez volta pro jogador
            $this->bloquearAcoes = false;
        }
    }

    private function executarMovimentoIA()
    {
        $tabuleiroIA = Tabuleiro::where('partida_id', $this->partida->id)->whereNull('user_id')->first();
        $grid = $tabuleiroIA->tabuleiro_grid;

        // 1. Coletar IDs de navios da IA que ainda não foram totalmente afundados
        $idsNavios = [];
        foreach ($grid as $linha) {
            foreach ($linha as $celula) {
                if (isset($celula['ship_id']) && $celula['status'] !== 'afundado') {
                    $idsNavios[] = $celula['ship_id'];
                }
            }
        }
        $idsNavios = array_unique($idsNavios);

        if (empty($idsNavios)) return;

        // 2. Escolher um navio aleatório e uma direção aleatória
        $idSorteado = $idsNavios[array_rand($idsNavios)];
        $direcoes = ['norte', 'sul', 'leste', 'oeste'];
        shuffle($direcoes);

        // 3. Tentar mover (Reutiliza a lógica de validação do passo 2 que você já implementou)
        foreach ($direcoes as $dir) {
            if ($this->validarEMoverNavioNoGrid($grid, $idSorteado, $dir)) {
                $tabuleiroIA->update(['tabuleiro_grid' => $grid]);
                $this->carregarTabuleiros();
                break;
            }
        }
    }

    // Método para o clique no navio (Radar Amigo)
    public function selecionarNavioParaMover($id)
    {
        if ($this->jaMoveuNesteTurno || $this->bloquearAcoes) return;
        $this->navioParaMover = $id;
    }

    // Helper essencial usado tanto pelo jogador quanto pela IA
    private function validarEMoverNavioNoGrid(&$grid, $idNavio, $direcao)
    {
        $coordsAntigas = [];
        $coordsNovas = [];

        foreach ($grid as $r => $linha) {
            foreach ($linha as $c => $celula) {
                if (($celula['ship_id'] ?? null) === $idNavio) {
                    // Bloqueia o movimento se o navio tiver qualquer parte atingida ou estiver afundado
                    if ($celula['status'] === 'acerto' || $celula['status'] === 'afundado') {
                        return false;
                    }

                    $coordsAntigas[] = ['r' => $r, 'c' => $c, 'dados' => $celula];
                    $nr = $r;
                    $nc = $c;

                    if ($direcao === 'norte') $nr--;
                    elseif ($direcao === 'sul') $nr++;
                    elseif ($direcao === 'leste') $nc++;
                    elseif ($direcao === 'oeste') $nc--;

                    if ($nr < 0 || $nr > 9 || $nc < 0 || $nc > 9) return false;
                    $coordsNovas[] = ['r' => $nr, 'c' => $nc];
                }
            }
        }

        foreach ($coordsNovas as $nova) {
            $celulaAlvo = $grid[$nova['r']][$nova['c']];
            if (isset($celulaAlvo['ship_id']) && $celulaAlvo['ship_id'] !== $idNavio) return false;
        }

        foreach ($coordsAntigas as $antiga) {
            $grid[$antiga['r']][$antiga['c']] = ['status' => 'agua', 'navio' => null];
        }
        foreach ($coordsNovas as $idx => $nova) {
            $grid[$nova['r']][$nova['c']] = $coordsAntigas[$idx]['dados'];
        }
        return true;
    }

    // ----------- MOVER NAVIO -----------

    public function moverNavio($direcao) // 'norte', 'sul', 'leste', 'oeste'
    {
        if ($this->jaMoveuNesteTurno || !$this->navioParaMover || $this->bloquearAcoes) return;

        if ($this->validarEMoverNavioNoGrid($this->meuTabuleiro, $this->navioParaMover, $direcao)) {
            $this->salvarMeuTabuleiro();
            $this->jaMoveuNesteTurno = true;
            $this->navioParaMover = null;
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
        $oponenteAfundado = $this->todosAfundados($this->tabuleiroOponente);
        $euAfundado       = $this->todosAfundados($this->meuTabuleiro);

        if ($oponenteAfundado || $euAfundado) {
            $this->partida->update([
                'status'      => 'finalizada',
                'finished_at' => now(),
            ]);

            // No PvP, ambos os jogadores precisam ter o ranking registado
            $this->calcularERegistrarRanking($oponenteAfundado);
            $this->fase = 'finalizada';

            // Notifica o oponente que o jogo acabou (opcional mas recomendado)
            if ($this->partida->eMultiplayer()) {
                broadcast(new JogadaRealizada($this->partida->id, 'fim_jogo', []))->toOthers();
            }
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
        if (array_sum($this->statusFrota) > 0) return;

        if ($this->partida->eMultiplayer()) {
            $souJogador1 = Auth::id() === $this->partida->criado_por;

            // 1. Atualiza prontidão (Certifique-se que estão no $fillable do Model Partida)
            if ($souJogador1) {
                $this->partida->update(['jogador1_pronto' => true]);
            } else {
                $this->partida->update(['jogador2_pronto' => true]);
            }

            // 2. Bloqueia localmente para mostrar o overlay de espera
            $this->bloquearAcoes = true;

            // 3. Notifica o oponente
            broadcast(new JogadaRealizada($this->partida->id, 'ready', []))->toOthers();

            // 4. Verifica se ambos estão prontos para virar a fase
            $this->partida->refresh();
            if ($this->partida->jogador1_pronto && $this->partida->jogador2_pronto) {
                $this->partida->update([
                    'status' => 'em_andamento',
                    'started_at' => now()
                ]);
                $this->fase = 'batalha';
                $this->carregarTabuleiros(); // Recarrega para ver o grid do inimigo
                $this->bloquearAcoes = ($this->partida->turno_atual_id !== Auth::id());
            }
        } else {
            // Modo IA
            $this->posicionarIA();
            $this->partida->update(['status' => 'em_andamento', 'started_at' => now()]);
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
                        $idNavio = uniqid($tipo . '_');
                        for ($i = 0; $i < $config['tamanho']; $i++) {
                            $nr = $r;
                            $nc = $c;
                            if ($dir === 0) $nc += $i;
                            elseif ($dir === 1) $nr += $i;
                            elseif ($dir === 2) $nc -= $i;
                            else $nr -= $i;

                            $grid[$nr][$nc] = [
                                'status'  => 'agua',
                                'navio'   => $tipo,
                                'ship_id' => $idNavio,
                                'cor'     => $config['cor'],
                                'parte'   => $i,
                                'tamanho' => $config['tamanho'],
                                'direcao' => $dir,
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
            $nr = $r;
            $nc = $c;
            if ($dir === 0) $nc += $i;
            elseif ($dir === 1) $nr += $i;
            elseif ($dir === 2) $nc -= $i;
            else $nr -= $i;

            if ($nr < 0 || $nr > 9 || $nc < 0 || $nc > 9 || isset($grid[$nr][$nc]['ship_id'])) {
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
    $queryInimigo = Tabuleiro::where('partida_id', $this->partida->id);
    if ($this->partida->eMultiplayer()) {
        $queryInimigo->where('user_id', '!=', Auth::id());
    } else {
        $queryInimigo->whereNull('user_id');
    }
    $tabuleiroAlvo = $queryInimigo->first();

    $tirosDados      = $tabuleiroAlvo->tiros()->count();
    $acertos         = $tabuleiroAlvo->tiros()->where('foi_atingido', true)->count();
    $naviosAfundados = $tabuleiroAlvo->tiros()->where('navio_afundado', true)->count();
    $tempo           = $this->partida->started_at
        ? (int) $this->partida->started_at->diffInSeconds(now())
        : 0;

    $base = match ($this->partida->dificuldade) {
        'facil'   => 100,
        'medio'   => 250,
        'dificil' => 500,
        default   => 200,
    };

    $bonusPrecisao = $tirosDados > 0 ? (int) round(($acertos / $tirosDados) * 200) : 0;
    $bonusTempo    = max(0, 300 - (int) floor($tempo / 10));
    $bonusVitoria  = $venceu ? 300 : 0;

    $ranking = Ranking::updateOrCreate(
        ['user_id' => Auth::id(), 'partida_id' => $this->partida->id],
        [
            'venceu'           => $venceu,
            'dificuldade'      => $this->partida->dificuldade ?? 'medio',
            'tiros_dados'      => $tirosDados,
            'acertos'          => $acertos,
            'navios_afundados' => $naviosAfundados,
            'tempo_segundos'   => $tempo,
            'pontuacao'        => $base + $bonusPrecisao + $bonusTempo + $bonusVitoria,
        ]
    );

    // ── NOVO: avalia e concede medalhas ──
    (new MedalhaService())->avaliarEConceder(Auth::id(), $this->partida, $ranking);
}
}
