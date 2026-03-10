<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JogadaRealizada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param int $partidaId ID da partida em curso
     * @param string $tipo 'tiro' ou 'movimento'
     * @param array $dados Coordenadas ['r' => x, 'c' => y]
     */
    public function __construct(
        public $partidaId,
        public $tipo,
        public $dados
    ) {}

    /**
     * Define o canal privado da partida.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("partida.{$this->partidaId}"),
        ];
    }

    /**
     * Nome do evento que o frontend (Echo) vai ouvir.
     */
    public function broadcastAs(): string
    {
        return 'JogadaRealizada';
    }
}