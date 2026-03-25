<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
  >
  <title>Host — Conhecimento é Poder</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
  <link
    href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Rajdhani:wght@400;500;600&display=swap"
    rel="stylesheet"
  >
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    :root {
      --hw: #00d4ff;
      --sw: #ff8c00;
      --bg: #07090f;
      --surface: #0d1117;
      --panel: #131923;
      --border: rgba(255, 255, 255, 0.07);
      --text: #dce8f8;
      --muted: rgba(220, 232, 248, 0.38);
      --gold: #ffd600;
    }

    body {
      font-family: 'Rajdhani', sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
    }

    .host-wrap {
      max-width: 800px;
      margin: 0 auto;
      padding: 2rem 1rem;
      text-align: center;
    }

    .title {
      font-family: 'Orbitron', sans-serif;
      font-size: 2rem;
      background: linear-gradient(100deg, var(--hw), var(--sw));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .lobby-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 2rem;
      margin-top: 2rem;
      box-shadow: 0 0 40px rgba(0, 0, 0, 0.5);
    }

    .code-display {
      font-family: 'Orbitron', sans-serif;
      font-size: 5rem;
      color: var(--gold);
      letter-spacing: 10px;
      margin: 1rem 0;
      text-shadow: 0 0 20px rgba(255, 214, 0, 0.4);
    }

    .player-list {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
      gap: 1rem;
      margin-top: 2rem;
    }

    .player-pill {
      background: var(--panel);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 0.8rem;
      font-weight: 600;
      font-size: 0.9rem;
    }

    .btn-action {
      font-family: 'Orbitron', sans-serif;
      font-size: 1.1rem;
      padding: 1rem 2rem;
      border-radius: 15px;
      letter-spacing: 2px;
      transition: all 0.3s;
    }

    .ranking-live {
      background: var(--panel);
      border-radius: 15px;
      margin-top: 2rem;
      overflow: hidden;
    }

    .ranking-live table {
      width: 100%;
      text-align: left;
    }

    .ranking-live th {
      background: rgba(255, 255, 255, 0.05);
      padding: 1rem;
      color: var(--muted);
      font-size: 0.7rem;
      text-transform: uppercase;
    }

    .ranking-live td {
      padding: 1rem;
      border-bottom: 1px solid var(--border);
      font-family: 'Orbitron', sans-serif;
      font-size: 1rem;
    }
  </style>
</head>

<body>
  <div class="host-wrap">
    <h1 class="title">PAINEL DO PROFESSOR</h1>

    <div
      id="setup-view"
      class="lobby-card"
    >
      <h3>Criar Sala de Duelo</h3>
      <button
        class="btn btn-primary btn-action mt-4"
        onclick="criarSala()"
      >CRIAR SALA AGORA 🚀</button>
    </div>

    <div
      id="lobby-view"
      class="lobby-card"
      style="display:none"
    >
      <div
        class="badge bg-primary mb-2"
        style="font-family:'Orbitron'"
      >AGUARDANDO ALUNOS</div>
      <div
        class="code-display"
        id="lobby-code"
      >000000</div>
      <div style="margin-top:8px">
        <button
          id="btn-copy"
          class="btn btn-outline-light btn-action"
          style="display:none;margin-right:8px"
          onclick="copyLink()"
        >COPIAR LINK DA SALA</button>
      </div>
      <div id="player-count-label">Alunos: 0</div>
      <div
        class="player-list"
        id="players-list-lobby"
      ></div>
      <button
        class="btn btn-success btn-action mt-5"
        onclick="iniciarJogo()"
      >INICIAR DESAFIO 🎮</button>
    </div>

    <div
      id="game-view"
      class="lobby-card"
      style="display:none"
    >
      <div
        style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;"
      >
        <h3
          id="current-q-label"
          style="margin:0; font-family:'Orbitron'; color:var(--hw)"
        >Questão 1 de 15</h3>
        <div
          class="badge bg-danger"
          style="animation: timerBlink 1s infinite; font-family:'Orbitron'; font-size:0.7rem; padding: 0.5rem 1rem;"
        >● AO VIVO</div>
      </div>

      <div
        class="ranking-live"
        style="background: var(--surface); border: 1px solid var(--border); border-radius: 15px; overflow: hidden;"
      >
        <table class="table table-dark table-hover mb-0">
          <thead style="background: rgba(255,255,255,0.03)">
            <tr>
              <th style="width:80px; padding: 1rem; border:none">POS</th>
              <th style="padding: 1rem; border:none">JOGADOR</th>
              <th
                class="text-end"
                style="padding: 1rem; border:none"
              >PONTUAÇÃO</th>
            </tr>
          </thead>
          <tbody id="live-ranking-body">
            <!-- Ranking em tempo real entra aqui -->
          </tbody>
        </table>
      </div>

      <div class="d-flex gap-3 justify-content-center mt-5">
        <button
          id="btn-next"
          class="btn btn-warning btn-action"
          onclick="proximaPergunta()"
        >AVANÇAR RODADA ❯</button>
        <button
          id="btn-end"
          class="btn btn-danger btn-action"
          style="display:none"
          onclick="encerrarSala()"
        >FINALIZAR SHOW 🏆</button>
      </div>
    </div>
  </div>

  <style>
    @keyframes timerBlink {

      0%,
      100% {
        opacity: 1;
      }

      50% {
        opacity: 0.5;
      }
    }

    .table-dark {
      --bs-table-bg: transparent;
    }
  </style>

  <script>
    const TOTAL_QUESTIONS = 15;
    let sessaoId = null, currentQ = -1, pollInterval = null;
    let prevPlayerCount = 0;

    async function criarSala() {
      try {
        const res = await axios.get('api_host.php?action=criar&tema=geral');
        if (res.data.sucesso) {
          sessaoId = res.data.sessao_id;
          document.getElementById('lobby-code').textContent = res.data.codigo;
          // Mostrar botão de copiar link e ajustar sua ação
          document.getElementById('btn-copy').style.display = 'inline-block';
          document.getElementById('setup-view').style.display = 'none';
          document.getElementById('lobby-view').style.display = 'block';
          startPolling();
        }
      } catch (e) { Swal.fire('Erro', 'Não foi possível conectar ao servidor.', 'error'); }
    }

    function startPolling() {
      pollInterval = setInterval(async () => {
        try {
          const res = await axios.get(`api_host.php?action=status&sessao_id=${sessaoId}`);
          if (res.data.sucesso) updateUI(res.data);
        } catch (e) { console.error("Erro na atualização"); }
      }, 2000);
    }

    function updateUI(data) {
      const { sessao, jogadores } = data;
      currentQ = parseInt(sessao.indice_pergunta_atual);

      const countEl = document.getElementById('player-count-label');
      const newCount = jogadores.length;
      // notificar saída se diminuiu
      if (newCount < prevPlayerCount) {
        Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: 'Um aluno saiu da sala', showConfirmButton: false, timer: 1600 });
      }
      if (countEl) countEl.textContent = `Alunos conectados: ${newCount}`;

      if (sessao.status === 'aguardando') {
        document.getElementById('players-list-lobby').innerHTML = jogadores.map(j => `
          <div class="player-pill" style="border: 1px solid var(--hw); color: var(--hw)">
            👤 ${j.nome.toUpperCase()}
          </div>
        `).join('');
      } else if (sessao.status === 'ativa') {
        document.getElementById('lobby-view').style.display = 'none';
        document.getElementById('game-view').style.display = 'block';
        document.getElementById('current-q-label').textContent = `QUESTÃO ${currentQ + 1} DE ${TOTAL_QUESTIONS}`;

        // Sempre permitir encerrar a sala manualmente durante o jogo
        document.getElementById('btn-end').style.display = 'inline-block';
        if (currentQ >= TOTAL_QUESTIONS - 1) {
          document.getElementById('btn-next').style.display = 'none';
        } else {
          document.getElementById('btn-next').style.display = 'inline-block';
        }

        // Ranking ao Vivo com Estilo CEP
        document.getElementById('live-ranking-body').innerHTML = jogadores.map((j, i) => `
          <tr style="border-bottom: 1px solid rgba(255,255,255,0.05)">
            <td class="py-3 px-4">
              <span class="badge ${i === 0 ? 'bg-warning text-dark' : 'bg-secondary'}" style="font-family:'Orbitron'; font-size:0.9rem">
                ${i + 1}º
              </span>
            </td>
            <td class="py-3">
              <div style="display:flex; align-items:center; gap:12px">
                <span style="font-size:1.4rem">${j.avatar || '👤'}</span>
                <span style="font-weight:700; letter-spacing:1px; font-size:1rem">${j.nome.toUpperCase()}</span>
              </div>
            </td>
            <td class="py-3 px-4 text-end">
              <span style="color:var(--gold); font-family:'Orbitron'; font-weight:900; font-size:1.2rem">
                ${j.pontuacao} <small style="font-size:0.6rem; opacity:0.6">PTS</small>
              </span>
            </td>
          </tr>
        `).join('');
      } else {
        clearInterval(pollInterval);
        Swal.fire({ title: 'SHOW ENCERRADO!', icon: 'success', confirmButtonColor: '#5c6bc0' }).then(() => location.reload());
      }

      prevPlayerCount = newCount;
    }

    function iniciarJogo() { axios.get(`api_host.php?action=iniciar&sessao_id=${sessaoId}`); }
    function proximaPergunta() { axios.get(`api_host.php?action=proxima&sessao_id=${sessaoId}`); }
    async function encerrarSala() {
      const confirm = await Swal.fire({ title: 'Finalizar Partida?', text: 'Isso revelará o pódio final para todos.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ff1744' });
      if (!confirm.isConfirmed) return;
      try {
        await axios.get(`api_host.php?action=encerrar&sessao_id=${sessaoId}`);
        // Buscar ranking final e exibir modal com opção de jogar novamente
        const res = await axios.get(`api_host.php?action=status&sessao_id=${sessaoId}&include_absent=1`);
        const jogadores = (res.data.jogadores || []).sort((a, b) => b.pontuacao - a.pontuacao);
        const colors = ['var(--gold)', '#c0c0c0', '#cd7f32'];
        const html = jogadores.map((r, i) => `
          <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 12px;color:${colors[i] || '#fff'};font-weight:bold">
            <span>${i === 0 ? '🥇' : i === 1 ? '🥈' : i === 2 ? '🥉' : `${i + 1}º`} ${r.nome.toUpperCase()}</span>
            <span style="font-family:'Orbitron',sans-serif">${r.pontuacao} pts</span>
          </div>
        `).join('');

        await Swal.fire({
          title: '🏆 PÓDIO FINAL 🏆',
          html: html,
          confirmButtonText: '🔄 JOGAR NOVAMENTE',
          confirmButtonColor: '#5c6bc0',
          allowOutsideClick: false
        });

        // Volta ao painel inicial para criar nova sala
        location.reload();
      } catch (e) {
        console.error(e);
        Swal.fire('Erro', 'Não foi possível encerrar a sala.', 'error');
      }
    }

    function copyLink() {
      const code = document.getElementById('lobby-code').textContent.trim();
      if (!code) return Swal.fire('Erro', 'Código não encontrado.', 'error');
      const base = location.origin + location.pathname.replace('host.html', 'index.php');
      const url = `${base}?codigo=${encodeURIComponent(code)}`;
      navigator.clipboard.writeText(url).then(() => {
        Swal.fire({ icon: 'success', title: 'Link copiado!', text: url, showConfirmButton: false, timer: 1800 });
      }).catch(() => {
        Swal.fire('Erro', 'Não foi possível copiar o link.', 'error');
      });
    }
  </script>
</body>

</html>