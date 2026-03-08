<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        // Ranking global — melhor pontuação por jogador
        $global = Ranking::with('user')
            ->selectRaw('user_id, MAX(pontuacao) as melhor_pontuacao,
                        COUNT(*) as total_partidas,
                        SUM(CASE WHEN venceu = true THEN 1 ELSE 0 END) as vitorias,
                        ROUND(AVG(CAST(acertos AS DECIMAL) / NULLIF(tiros_dados, 0)) * 100) as precisao_media')
            ->groupBy('user_id')
            ->orderByDesc('melhor_pontuacao')
            ->limit(10)
            ->get();

        // Ranking pessoal — histórico do usuário logado
        $pessoal = Ranking::where('user_id', Auth::id())
            ->orderByDesc('pontuacao')
            ->limit(20)
            ->get();

        return view('ranking.index', compact('global', 'pessoal'));
    }
}
