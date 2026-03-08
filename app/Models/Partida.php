<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    /** @use HasFactory<\Database\Factories\PartidaFactory> */
    use HasFactory;

    protected $fillable = ['modo', 'dificuldade', 'status', 'criado_por', 'started_at', 'user_id'];

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
}
