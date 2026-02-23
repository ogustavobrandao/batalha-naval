<?php

use App\Services\AI\Strategies\EstrategiaCacador;

it('targets a neighbor of a recent hit that did not sink the ship', function () {
    // Cria um "tiro" que representa um acerto em (4,4)
    $shot = new stdClass();
    $shot->x = 4;
    $shot->y = 4;
    $shot->is_hit = true;
    $shot->ship_sunk = false;

    // Board fake
    $board = new class($shot) extends App\Models\Board {
        private $shotObj;
        public function __construct($shot)
        {
            $this->shotObj = $shot;
        }
        public function shots()
        {
            return new class($this->shotObj) {
                private $shot;
                public function __construct($shot)
                {
                    $this->shot = $shot;
                }
                public function where($col, $val)
                {
                    // Encadeia chamadas minimalistas usadas pelo HunterStrategy
                    return $this;
                }
                public function latest()
                {
                    return $this;
                }
                public function first()
                {
                    return $this->shot;
                }
            };
        }

        public function hasShotAt($x, $y)
        {
            // Assume que nenhum vizinho foi atirado ainda
            return false;
        }
    };

    $strategy = new EstrategiaCacador();
    $result = $strategy->escolherAlvo($board);

    // Deve ser um vizinho de (4,4)
    $dx = abs($result['x'] - 4);
    $dy = abs($result['y'] - 4);

    // Deve estar a distância Manhatan 1
    expect($dx + $dy)->toBeOne();
    expect($result['x'])->toBeBetween(0, 9);
    expect($result['y'])->toBeBetween(0, 9);
});
