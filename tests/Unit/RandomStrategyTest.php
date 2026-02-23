<?php

use App\Services\AI\Strategies\EstrategiaAleatoria;
use Illuminate\Support\Collection;

// Testa que, quando todas as posições exceto uma estão ocupadas, o RandomStrategy devolve a posição livre
it('returns the only available cell when all others are occupied', function () {
    // Gera todas as coordenadas exceto (5,5)
    $shots = [];
    for ($x = 0; $x < 10; $x++) {
        for ($y = 0; $y < 10; $y++) {
            if ($x === 5 && $y === 5) continue;
            $s = new stdClass();
            $s->x = $x;
            $s->y = $y;
            $shots[] = $s;
        }
    }

    // Board fake que atende às chamadas usadas por RandomStrategy
    $board = new class($shots) extends App\Models\Board {
        public array $shotsArray;
        public function __construct(array $shots = [])
        {
            $this->shotsArray = $shots;
        }
        public function shots()
        {
            return new class($this->shotsArray) {
                private array $shots;
                public function __construct(array $shots)
                {
                    $this->shots = $shots;
                }
                public function get($cols = null)
                {
                    return collect($this->shots);
                }
            };
        }
    };

    $strategy = new EstrategiaAleatoria();
    $result = $strategy->escolherAlvo($board);

    expect($result)->toBeArray();
    expect($result['x'])->toBe(5);
    expect($result['y'])->toBe(5);
});
