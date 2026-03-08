<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    protected $fillable = [
        'user_id', 'partida_id', 'venceu', 'dificuldade',
        'tiros_dados', 'acertos', 'navios_afundados',
        'tempo_segundos', 'pontuacao',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partida()
    {
        return $this->belongsTo(Partida::class);
    }

    // Precisão em %
    public function getPrecisaoAttribute(): int
    {
        if ($this->tiros_dados === 0) return 0;
        return (int) round(($this->acertos / $this->tiros_dados) * 100);
    }
}
