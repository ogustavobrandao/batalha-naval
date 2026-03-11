<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartidaStoreRequest;
use App\Models\Partida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $modo = $request->query('modo', 'pve');
        $dificuldade = $request->query('dificuldade', 'facil'); // <- adicionar essa linha
        return view('campanha_dificuldade', compact('modo', 'dificuldade'));
    }

    public function modos()
    {
        return view('modos_jogo');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PartidaStoreRequest $request)
    {
        $partida = Partida::create(array_merge(
            $request->validated(),
            [
                'started_at' => now(),
                'criado_por' => Auth::id(),
                'status' => 'posicionamento',
            ]
        ));

        // Cria tabuleiro do jogador (vazio 10x10)
        $partida->tabuleiros()->create([
            'user_id' => Auth::id(),
            'tabuleiro_grid' => array_fill(0, 10, array_fill(0, 10, ['status' => 'agua', 'navio' => null]))
        ]);

        // Cria o tabuleiro da IA (vazio 10x10)
        $partida->tabuleiros()->create([
            'user_id' => null,
            'tabuleiro_grid' => array_fill(0, 10, array_fill(0, 10, ['status' => 'agua', 'navio' => null]))
        ]);

        return redirect()->route('jogo.batalha', $partida->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Partida $partida)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partida $partida)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partida $partida)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partida $partida)
    {
        //
    }
}
