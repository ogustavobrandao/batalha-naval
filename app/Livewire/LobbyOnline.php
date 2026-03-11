<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Partida;
use App\Models\Tabuleiro;
use Illuminate\Support\Facades\Auth;

class LobbyOnline extends Component
{
public function criarDesafio()
{
    $partida = Partida::create([
        'modo' => 'pvp',
        'status' => 'aguardando',
        'criado_por' => auth()->id(),
        'turno_atual_id' => auth()->id(),
        'dificuldade' => 'medio',
    ]);

    $partida->tabuleiros()->create([
        'user_id' => auth()->id(),
        'tabuleiro_grid' => array_fill(0, 10, array_fill(0, 10, ['status' => 'agua', 'navio' => null]))
    ]);

    return redirect()->route('jogo.batalha', $partida->id);
}

    public function aceitarDesafio($partidaId)
    {
        $partida = Partida::findOrFail($partidaId);

        if ($partida->criado_por === Auth::id() || $partida->jogador2_id) {
            return;
        }

        $partida->update([
            'jogador2_id' => Auth::id(),
            'status' => 'posicionamento',
        ]);

        // Cria o tabuleiro para o Jogador 2 se não existir
        Tabuleiro::firstOrCreate(
            ['partida_id' => $partida->id, 'user_id' => Auth::id()],
            ['tabuleiro_grid' => array_fill(0, 10, array_fill(0, 10, ['status' => 'agua', 'navio' => null]))]
        );

        return redirect()->route('jogo.batalha', $partida->id);
    }

    public function render()
    {
        return view('livewire.lobby-online', [
            // Lista apenas salas PvP que estão aguardando oponente
            'desafios' => Partida::where('status', 'aguardando')
                ->where('modo', 'pvp')
                ->where('criado_por', '!=', Auth::id())
                ->latest()
                ->get()
        ]);
    }
}
