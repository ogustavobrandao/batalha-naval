<div align="center">

# ⚓ Batalha Naval

**Jogo de Batalha Naval online com IA, ranking e múltiplas dificuldades**

![Status](https://img.shields.io/badge/status-em%20desenvolvimento-yellow?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-336791?style=for-the-badge&logo=postgresql&logoColor=white)

</div>

---

## 📋 Sobre o Projeto

Batalha Naval é uma aplicação web que recria o clássico jogo de tabuleiro de forma digital. O jogador posiciona sua frota estrategicamente e enfrenta uma **Inteligência Artificial** com comportamento adaptável conforme a dificuldade escolhida. Ao final de cada partida, uma pontuação é calculada com base na precisão, tempo e dificuldade, alimentando um **ranking global** entre os usuários.

> Projeto desenvolvido como trabalho acadêmico no curso de Bacharelado em Ciência da Computação — UFAPE.

---

## ✨ Funcionalidades

- 🔐 **Autenticação** — Registro e login de usuários
- 🚢 **Posicionamento de frota** — Interface drag-and-drop com rotação de navios em 4 direções
- 🤖 **IA como oponente** — Comportamento ajustado por nível de dificuldade
- 🎯 **Combate em tempo real** — Interface reativa com Livewire sem recarregar a página
- 🏆 **Sistema de Ranking** — Pontuação baseada em dificuldade, precisão e tempo
- 📊 **Histórico pessoal** — Acompanhe suas melhores partidas

---

## 🛠️ Tecnologias

| Camada | Tecnologia |
|--------|-----------|
| Backend | PHP 8.2 + Laravel 12 |
| Frontend Reativo | Livewire 3 |
| Estilização | Tailwind CSS |
| Banco de Dados | PostgreSQL |
| Ambiente | Laragon/php serve |

---

## 🚀 Como Rodar Localmente

### Pré-requisitos

- PHP 8.2+
- Composer
- Node.js + NPM
- PostgreSQL
- Laragon (recomendado)

### Passo a passo

```bash
# 1. Clone o repositório
git clone https://github.com/seu-usuario/batalha-naval.git
cd batalha-naval

# 2. Instale as dependências PHP
composer install

# 3. Instale as dependências JS
npm install

# 4. Configure o ambiente
cp .env.example .env
php artisan key:generate
```

Configure seu `.env` com as credenciais do banco:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=batalha_naval
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

```bash
# 5. Execute as migrations
php artisan migrate

# 6. Compile os assets
npm run dev

# 7. Suba o servidor
php -S 0.0.0.0:8000 -t public
```

Acesse: [http://localhost:8000](http://localhost:8000)

---

## 🎮 Como Jogar

1. **Crie uma conta** ou faça login
2. **Escolha a dificuldade** — Fácil, Médio ou Difícil
3. **Posicione sua frota** no tabuleiro (clique para posicionar, clique no navio para remover, use o botão girar para mudar a direção)
4. **Inicie a batalha** e ataque as células do tabuleiro inimigo
5. Afunde todos os navios da IA antes que ela afunde os seus
6. Veja sua **pontuação e posição no ranking** ao final da partida

---

## 🧮 Sistema de Pontuação

| Componente | Pontos |
|-----------|--------|
| Base (Fácil) | 100 pts |
| Base (Médio) | 250 pts |
| Base (Difícil) | 500 pts |
| Bônus de Precisão | até 200 pts |
| Bônus de Tempo | até 300 pts |
| Bônus de Vitória | 300 pts |

---

## 📁 Estrutura Relevante

```
app/
├── Livewire/
│   └── JogoBatalha.php       # Lógica principal do jogo
├── Models/
│   ├── Partida.php
│   ├── Tabuleiro.php
│   └── Ranking.php
├── Http/Controllers/
│   └── RankingController.php
└── Services/AI/
    └── AISelector.php        # Seletor de estratégia da IA

resources/views/
├── livewire/
│   └── tabuleiro.blade.php   # Interface do jogo
└── ranking/
    └── index.blade.php       # Página de ranking
```

---

## 🗺️ Roadmap

- [x] Autenticação de usuários
- [x] Posicionamento de frota
- [x] Combate contra IA
- [x] Múltiplas dificuldades
- [x] Sistema de ranking e pontuação
- [ ] Modo multiplayer em tempo real
- [ ] Sons e efeitos visuais
- [ ] Animações de explosão ao afundar navios
- [ ] Perfil do jogador com estatísticas detalhadas

---

## 👤 Autores

Desenvolvido por **Douglas Henrique, Antônio Gustavo, Genildo Burgos, Paulo Eduardo**

[![GitHub](https://img.shields.io/badge/GitHub-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/douglaskks/batalha-naval)


---

<div align="center">
  <sub>Projeto acadêmico — UFAPE • Ciência da Computação</sub>
</div>
