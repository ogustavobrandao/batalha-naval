<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    /** @use HasFactory<\Database\Factories\PartidaFactory> */
    use HasFactory;

    protected $fillable = ['modo', 'dificuldade', 'status', 'criado_por', 'started_at', 'user_id', 'jogador2_id', 'turno_atual_id', 'jogador1_pronto', 'jogador2_pronto'];

    protected $casts = [
        'started_at' => 'datetime',
    ];

    public function user()
    {
        // return $this->belongsToMany(User::class, 'partida_user', 'user_id');
        return $this->belongsToMany(User::class, 'partida_user', 'partida_id', 'user_id');
    }

    public function tabuleiros()
    {
        return $this->hasMany(Tabuleiro::class);
    }

    public function eMultiplayer(): bool
    {
        return $this->modo === 'pvp';
    }

    public function jogador1()
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    public function jogador2()
    {
        return $this->belongsTo(User::class, 'jogador2_id');
    }

    public function souCriador()
    {
        return $this->criado_por === auth()->id();
    }
}
