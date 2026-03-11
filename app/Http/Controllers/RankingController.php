<?php

namespace App\Http\Controllers;

use App\Models\Medalha;
use App\Models\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        $filtro = $request->input('filtro', 'geral');

        // ── Ranking global agregado por jogador ──────────────────────────
        $query = Ranking::with('user')
            ->select(
                'user_id',
                DB::raw('SUM(pontuacao) as pontuacao_total'),
                DB::raw('SUM(CASE WHEN venceu = true THEN 1 ELSE 0 END) as vitorias'),
                DB::raw('SUM(CASE WHEN venceu = false THEN 1 ELSE 0 END) as derrotas'),
                DB::raw('COUNT(*) as total_partidas')
            )
            ->groupBy('user_id')
            ->orderByDesc('pontuacao_total');

        // Filtro de período
        if ($filtro === 'mensal') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($filtro === 'semanal') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        $ranking = $query->limit(50)->get();

        // Adiciona contagem de medalhas em cada jogador
        $userIds = $ranking->pluck('user_id');

        $medalhasPorUser = Medalha::whereIn('user_id', $userIds)
            ->get(['user_id', 'tipo'])
            ->groupBy('user_id');

        $ranking->each(function ($row) use ($medalhasPorUser) {
            $medalhas = $medalhasPorUser[$row->user_id] ?? collect();
            $row->total_medalhas = $medalhas->count();
            $row->tipos_medalhas = $medalhas->pluck('tipo')->unique()->values()->toArray();
        });

        // ── Posição e stats do jogador logado ────────────────────────────
        $minhaPosicao  = $ranking->search(fn($r) => $r->user_id === Auth::id());
        $minhaPosicao  = $minhaPosicao !== false ? $minhaPosicao + 1 : null;

        $meusDados = $ranking->firstWhere('user_id', Auth::id());
        $minhasPrecisao = null;

        if ($meusDados) {
            // Precisão real: média dos registros individuais
            $stats = Ranking::where('user_id', Auth::id())
                ->selectRaw('SUM(acertos) as total_acertos, SUM(tiros_dados) as total_tiros')
                ->first();

            $minhasPrecisao = ($stats->total_tiros > 0)
                ? (int) round(($stats->total_acertos / $stats->total_tiros) * 100)
                : 0;
        }

        $minhasMedalhas = Medalha::where('user_id', Auth::id())->count();

        return view('ranking.index', compact(
            'ranking',
            'filtro',
            'minhaPosicao',
            'minhasPrecisao',
            'minhasMedalhas'
        ));
    }
}
