<?php

namespace Database\Seeders;

use App\Models\Medalha;
use App\Models\Ranking;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioRankingSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            ['name' => 'Almirante_Nelson', 'email' => 'nelson@batalha.com'],
            ['name' => 'DarkFleet_01',     'email' => 'dark@batalha.com'],
            ['name' => 'OceanHunter',      'email' => 'ocean@batalha.com'],
        ];

        foreach ($usuarios as $dados) {
            $user = User::firstOrCreate(
                ['email' => $dados['email']],
                ['name' => $dados['name'], 'password' => Hash::make('password')]
            );

            // 3 partidas fictícias por usuário
            foreach (range(1, 3) as $i) {
                Ranking::create([
                    'user_id'          => $user->id,
                    'partida_id'       => 1, // ajuste para um partida_id válido no seu banco
                    'venceu'           => true,
                    'dificuldade'      => ['facil', 'medio', 'dificil'][rand(0, 2)],
                    'tiros_dados'      => rand(20, 60),
                    'acertos'         => rand(10, 20),
                    'navios_afundados' => rand(3, 6),
                    'tempo_segundos'   => rand(60, 300),
                    'pontuacao'        => rand(400, 1200),
                ]);
            }

            // 1 medalha por usuário
            Medalha::firstOrCreate([
                'user_id'    => $user->id,
                'partida_id' => 1,
                'tipo'       => 'almirante',
            ]);
        }
    }
}
