<?php

use Illuminate\Support\Facades\Broadcast;
use \App\Models\Partida;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('partida.{id}', function ($user, $id) {
    $partida = Partida::find($id);
    return $partida && ($user->id === $partida->criado_por || $user->id === $partida->jogador2_id);
});