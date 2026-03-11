<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medalha extends Model
{
    protected $fillable = ['user_id', 'partida_id', 'tipo'];

    // Labels para exibição na view
    public const LABELS = [
        'almirante'         => 'Almirante',
        'capitao_mar_guerra'=> 'Capitão de Mar e Guerra',
        'capitao'           => 'Capitão',
        'marinheiro'        => 'Marinheiro',
    ];

    public const DESCRICOES = [
        'almirante'         => 'Venceu sem perder nenhum navio.',
        'capitao_mar_guerra'=> 'Acertou 8 tiros consecutivos.',
        'capitao'           => 'Acertou 7 tiros consecutivos.',
        'marinheiro'        => 'Venceu em menos de 3 minutos.',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partida()
    {
        return $this->belongsTo(Partida::class);
    }

    public function getLabelAttribute(): string
    {
        return self::LABELS[$this->tipo] ?? $this->tipo;
    }
}
