<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
  />
  <title>Conhecimento é Poder — Show de TV</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800;900&family=Rajdhani:wght@400;500;600;700&display=swap"
    rel="stylesheet"
  />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
      --accent: #5c6bc0;
      --ok: #00e676;
      --err: #ff1744;
      --gold: #ffd600;
      --ice: #00d4ff;
      --gloop: #00e676;
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Rajdhani', sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
      background-size: 48px 48px;
      animation: gridDrift 20s linear infinite;
      pointer-events: none;
      z-index: 0;
    }

    @keyframes gridDrift {
      from {
        background-position: 0 0
      }

      to {
        background-position: 48px 48px
      }
    }

    .wrap {
      position: relative;
      z-index: 2;
      max-width: 640px;
      margin: 0 auto;
      padding: 1rem;
    }

    /* ── Header ── */
    .site-header {
      text-align: center;
      padding: 0.8rem;
    }

    .site-title {
      font-family: 'Orbitron', sans-serif;
      font-size: 1.6rem;
      font-weight: 900;
      background: linear-gradient(100deg, var(--hw), var(--sw));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      filter: drop-shadow(0 0 12px rgba(0, 212, 255, 0.4));
    }

    /* ── HUD ── */
    .hud {
      display: flex;
      justify-content: space-around;
      margin-bottom: 0.8rem;
      flex-wrap: wrap;
      gap: 0.5rem;
    }

    .hud-chip {
      background: var(--panel);
      border: 1px solid var(--border);
      border-radius: 50px;
      padding: 0.4rem 1rem;
      font-size: 0.8rem;
      font-weight: 600;
      letter-spacing: 1px;
    }

    /* ── Timer bar ── */
    .timer-bar-container {
      width: 100%;
      height: 12px;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 10px;
      margin-bottom: 1rem;
      overflow: hidden;
      border: 1px solid var(--border);
    }

    .timer-bar-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--ok), var(--gold), var(--err));
      background-size: 200% 100%;
      width: 100%;
      transition: width 0.1s linear;
      animation: timerColorShift 15s linear forwards;
    }

    @keyframes timerColorShift {
      0% {
        background-position: 0% 0%;
      }

      100% {
        background-position: 100% 0%;
      }
    }

    /* ── Main card ── */
    .item-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 2rem 1.5rem;
      text-align: center;
      position: relative;
      min-height: 260px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
      overflow: hidden;
      margin-bottom: 1rem;
    }

    .item-icon {
      font-size: 3.5rem;
      display: block;
      margin-bottom: 0.8rem;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0)
      }

      50% {
        transform: translateY(-8px)
      }
    }

    .item-name {
      font-family: 'Orbitron', sans-serif;
      font-size: clamp(1rem, 3.5vw, 1.4rem);
      font-weight: 800;
      color: #fff;
      line-height: 1.3;
    }

    /* ── Buttons ── */
    .btn-area {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0.8rem;
      margin-bottom: 1rem;
    }

    .btn-choice {
      border: none;
      border-radius: 14px;
      padding: 1rem 0.8rem;
      font-family: 'Orbitron', sans-serif;
      font-size: 0.78rem;
      font-weight: 800;
      cursor: pointer;
      transition: all 0.15s;
      min-height: 80px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 4px 0 rgba(0, 0, 0, 0.4);
    }

    .btn-choice:active:not(:disabled) {
      transform: translateY(4px);
      box-shadow: none;
    }

    .btn-choice:disabled {
      opacity: 0.35;
      cursor: not-allowed;
    }

    .btn-hw {
      background: linear-gradient(135deg, #003d4d, #005566);
      color: var(--hw);
      border: 1px solid rgba(0, 212, 255, 0.4);
    }

    .btn-sw {
      background: linear-gradient(135deg, #3d2000, #5a2e00);
      color: var(--sw);
      border: 1px solid rgba(255, 140, 0, 0.4);
    }

    /* ════════════════════════════════════════
       PODER: GELO ❄️
       Efeito: cada botão vira um bloco de gelo — label oculta, ícone ❄️
       Dura 5s, depois revela os textos
    ════════════════════════════════════════ */
    .btn-choice.ice-effect {
      color: transparent !important;
      text-shadow: none !important;
      background: linear-gradient(135deg, #003d5c, #005580, #00aacc) !important;
      border: 2px solid var(--ice) !important;
      box-shadow: 0 0 20px rgba(0, 212, 255, 0.5), inset 0 0 20px rgba(0, 212, 255, 0.15) !important;
      cursor: not-allowed !important;
      animation: icePulse 1s ease infinite;
    }

    .btn-choice.ice-effect::before {
      content: '❄️';
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      animation: iceShimmer 2s ease infinite;
    }

    @keyframes icePulse {

      0%,
      100% {
        box-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
      }

      50% {
        box-shadow: 0 0 40px rgba(0, 212, 255, 0.9);
      }
    }

    @keyframes iceShimmer {

      0%,
      100% {
        opacity: 0.8;
        transform: scale(1);
      }

      50% {
        opacity: 1;
        transform: scale(1.2);
      }
    }

    /* Countdown badge no canto do card quando GELO ativo */
    #ice-countdown {
      display: none;
      position: absolute;
      top: 12px;
      right: 12px;
      background: rgba(0, 212, 255, 0.2);
      border: 1px solid var(--ice);
      border-radius: 50px;
      padding: 4px 12px;
      font-family: 'Orbitron', sans-serif;
      font-size: 0.75rem;
      color: var(--ice);
      z-index: 20;
    }

    #ice-countdown.active {
      display: block;
    }

    /* ════════════════════════════════════════
       PODER: GOSMA 🟢
       Efeito: overlay de gosma sobre o card inteiro.
       O aluno CLICA na gosma para limpar — só então os botões aparecem.
       Quanto mais demora, menos tempo de cronômetro sobra.
    ════════════════════════════════════════ */
    #gloop-overlay {
      position: absolute;
      inset: 0;
      border-radius: 20px;
      z-index: 30;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 0.8rem;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s;
    }

    #gloop-overlay.active {
      opacity: 1;
      pointer-events: all;
    }

    /* Gosma blobs via SVG filter */
    #gloop-overlay::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: 20px;
      background: radial-gradient(ellipse at 30% 40%, #00e676 0%, #00c853 40%, #1b5e20 100%);
      filter: url(#gloop-filter);
      z-index: -1;
    }

    .gloop-icon {
      font-size: 3.5rem;
      animation: gloopBounce 0.6s ease infinite alternate;
    }

    .gloop-label {
      font-family: 'Orbitron', sans-serif;
      font-size: 1rem;
      color: #fff;
      font-weight: 800;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
      letter-spacing: 2px;
    }

    .gloop-tap {
      font-size: 0.75rem;
      color: rgba(255, 255, 255, 0.7);
      letter-spacing: 3px;
      text-transform: uppercase;
      animation: tapBlink 0.8s ease infinite;
    }

    @keyframes gloopBounce {
      from {
        transform: translateY(0) rotate(-5deg)
      }

      to {
        transform: translateY(-10px) rotate(5deg)
      }
    }

    @keyframes tapBlink {

      0%,
      100% {
        opacity: 1
      }

      50% {
        opacity: 0.3
      }
    }

    /* Attacker toast — aparece no topo do card */
    #attacker-toast {
      display: none;
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      z-index: 25;
      border-radius: 20px 20px 0 0;
      padding: 0.7rem 1rem;
      font-family: 'Orbitron', sans-serif;
      font-size: 0.75rem;
      font-weight: 800;
      letter-spacing: 1px;
      text-align: center;
      animation: toastSlide 0.4s ease forwards;
    }

    #attacker-toast.ice-toast {
      background: rgba(0, 180, 220, 0.9);
      color: #fff;
      border-bottom: 2px solid var(--ice);
    }

    #attacker-toast.gloop-toast {
      background: rgba(0, 200, 80, 0.9);
      color: #fff;
      border-bottom: 2px solid var(--gloop);
    }

    #attacker-toast.active {
      display: block;
    }

    @keyframes toastSlide {
      from {
        transform: translateY(-100%)
      }

      to {
        transform: translateY(0)
      }
    }

    /* ── Splash / Get-Ready ── */
    #splash-overlay,
    #get-ready-screen {
      position: absolute;
      inset: 0;
      border-radius: 20px;
      background: rgba(7, 9, 15, 0.97);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 1rem;
      z-index: 50;
    }

    .splash-pulse {
      animation: pulse 2s ease infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        transform: scale(1)
      }

      50% {
        transform: scale(1.1)
      }
    }

    .ready-title {
      font-size: 0.75rem;
      color: var(--muted);
      letter-spacing: 6px;
      text-transform: uppercase;
    }

    .ready-num {
      font-family: 'Orbitron', sans-serif;
      font-size: 5rem;
      color: var(--gold);
      text-shadow: 0 0 30px var(--gold);
      animation: readyPop 1s ease;
    }

    @keyframes readyPop {
      from {
        transform: scale(1.5);
        opacity: 0
      }

      to {
        transform: scale(1);
        opacity: 1
      }
    }

    /* ── Power phase timer ── */
    .power-phase-bar {
      width: 100%;
      height: 6px;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 10px;
      overflow: hidden;
    }

    .power-phase-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--ice), var(--accent));
      border-radius: 10px;
      transition: width 0.1s linear;
    }

    /* ── SweetAlert2 custom ── */
    .swal2-popup {
      font-family: 'Rajdhani', sans-serif !important;
      background: #0d1117 !important;
      border: 1px solid rgba(255, 255, 255, 0.1) !important;
      border-radius: 20px !important;
      color: #dce8f8 !important;
    }

    .swal2-title {
      font-family: 'Orbitron', sans-serif !important;
    }

    .swal2-confirm,
    .swal2-deny,
    .swal2-cancel {
      font-family: 'Orbitron', sans-serif !important;
      font-size: 0.78rem !important;
      letter-spacing: 1px !important;
      border-radius: 10px !important;
      padding: 0.65rem 1.8rem !important;
    }

    .swal2-confirm {
      background: #5c6bc0 !important;
    }

    /* Power choice buttons inside Swal */
    .power-btn {
      display: block;
      width: 100%;
      border: none;
      border-radius: 12px;
      padding: 1rem;
      margin-bottom: 0.7rem;
      font-family: 'Orbitron', sans-serif;
      font-size: 0.85rem;
      font-weight: 800;
      letter-spacing: 1px;
      cursor: pointer;
      transition: transform 0.15s, box-shadow 0.15s;
    }

    .power-btn:hover {
      transform: translateY(-2px);
    }

    .power-btn:last-child {
      margin-bottom: 0;
    }

    .pbtn-ice {
      background: linear-gradient(135deg, #003d5c, #006080);
      color: var(--ice);
      border: 1px solid var(--ice);
      box-shadow: 0 0 20px rgba(0, 212, 255, 0.2);
    }

    .pbtn-gloop {
      background: linear-gradient(135deg, #003320, #005030);
      color: var(--gloop);
      border: 1px solid var(--gloop);
      box-shadow: 0 0 20px rgba(0, 230, 118, 0.2);
    }

    .pbtn-skip {
      background: rgba(255, 255, 255, 0.05);
      color: rgba(220, 232, 248, 0.5);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Target selection */
    .target-btn {
      display: block;
      width: 100%;
      border: none;
      border-radius: 10px;
      padding: 0.8rem 1rem;
      margin-bottom: 0.5rem;
      background: var(--panel);
      border: 1px solid var(--border);
      color: var(--text);
      font-family: 'Rajdhani', sans-serif;
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 1px;
      cursor: pointer;
      text-align: left;
      transition: background 0.15s, border-color 0.15s;
    }

    .target-btn:hover {
      background: rgba(0, 212, 255, 0.1);
      border-color: var(--ice);
    }

    .target-btn:last-child {
      margin-bottom: 0;
    }

    .hidden {
      display: none !important;
    }

    /* Responsive */
    @media (max-width: 480px) {
      .btn-area {
        grid-template-columns: 1fr;
      }

      .item-name {
        font-size: 1rem;
      }
    }
  </style>
</head>

<body>

  <!-- SVG filter for gloop effect -->
  <svg style="position:absolute;width:0;height:0">
    <defs>
      <filter id="gloop-filter">
        <feGaussianBlur
          in="SourceGraphic"
          stdDeviation="8"
          result="blur"
        />
        <feColorMatrix
          in="blur"
          mode="matrix"
          values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 20 -8"
          result="gloop"
        />
        <feComposite
          in="SourceGraphic"
          in2="gloop"
          operator="atop"
        />
      </filter>
    </defs>
  </svg>

  <div class="wrap">
    <header class="site-header">
      <div class="site-title">⚡ CONHECIMENTO É PODER</div>
      <div
        id="site-sub"
        style="font-size:0.7rem;color:var(--muted);letter-spacing:2px;margin-top:0.3rem"
      >SHOW DE TALENTOS</div>
    </header>

    <div class="hud">
      <div class="hud-chip">🏆 <span id="hud-pts">0 pts</span></div>
      <div class="hud-chip">🎮 <span id="q-label">Aguardando...</span></div>
      <div
        class="hud-chip"
        id="power-status"
        style="display:none"
      >⚡ <span id="power-status-txt"></span></div>
      <div
        id="btn-leave"
        class="hud-chip"
        style="display:none;cursor:pointer"
      >⛔ DESISTIR</div>
    </div>

    <div class="timer-bar-container">
      <div
        id="timer-bar-fill"
        class="timer-bar-fill"
      ></div>
    </div>

    <div
      class="item-card"
      id="item-card"
    >

      <!-- Attacker toast (aparece no topo do card) -->
      <div id="attacker-toast"></div>

      <!-- Ice countdown badge -->
      <div id="ice-countdown">❄️ <span id="ice-sec">5</span>s</div>

      <!-- Gloop overlay (clicável) -->
      <div
        id="gloop-overlay"
        onclick="cleanGloop()"
      >
        <div class="gloop-icon">🟢</div>
        <div class="gloop-label">GOSMA!</div>
        <div class="gloop-tap">TOQUE PARA LIMPAR</div>
      </div>

      <!-- Splash waiting screen -->
      <div id="splash-overlay">
        <div
          class="splash-pulse"
          style="font-size:4rem"
        >🎮</div>
        <span
          style="font-family:'Orbitron';letter-spacing:2px;color:var(--hw)">AGUARDANDO
          O HOST...</span>
      </div>

      <!-- Question content -->
      <div
        id="question-content"
        class="hidden"
      >
        <span
          class="item-icon"
          id="item-icon"
        >⚡</span>
        <div
          class="item-name"
          id="item-name"
        >...</div>
      </div>

      <!-- Get ready countdown -->
      <div
        id="get-ready-screen"
        class="hidden"
      >
        <div
          class="ready-title"
          id="ready-label"
        >RODADA 1</div>
        <div
          class="ready-num"
          id="get-ready-timer"
        >5</div>
      </div>

    </div><!-- /item-card -->

    <!-- Answer buttons -->
    <div
      id="game-controls"
      class="hidden"
    >
      <div
        class="btn-area"
        id="btn-area"
      ></div>
    </div>

  </div><!-- /wrap -->

  <script>
    /* ══════════════════════════════════════════════════
       QUESTIONS
    ══════════════════════════════════════════════════ */
    const ITEMS = [
      {
        question: "Qual atalho (comando) usamos para COPIAR um texto ou arquivo no Windows?", icon: "📋",
        options: [{ label: "CTRL + C", isCorrect: true, feedback: "Correto! C de Copy — o atalho mais usado do dia a dia." }, { label: "CTRL + V", isCorrect: false, feedback: "CTRL+V serve para COLAR (Paste), não copiar." }, { label: "CTRL + X", isCorrect: false, feedback: "CTRL+X Recorta o conteúdo, como uma tesoura digital." }, { label: "CTRL + Z", isCorrect: false, feedback: "CTRL+Z Desfaz a última ação realizada." }]
      },
      {
        question: "Qual componente é conhecido como o 'Cérebro' do computador?", icon: "⚙️",
        options: [{ label: "CPU", isCorrect: true, feedback: "Exato! A CPU (Unidade Central de Processamento) executa todos os cálculos." }, { label: "RAM", isCorrect: false, feedback: "A RAM é a memória de trabalho temporária, não o cérebro." }, { label: "HD", isCorrect: false, feedback: "O HD é o armazenamento permanente de arquivos." }, { label: "Placa-Mãe", isCorrect: false, feedback: "A Placa-Mãe conecta os componentes, mas não processa." }]
      },
      {
        question: "O Windows, Linux e o macOS são exemplos de:", icon: "🖥️",
        options: [{ label: "Sistemas Operacionais", isCorrect: true, feedback: "Isso! Eles gerenciam o hardware e os programas do PC." }, { label: "Navegadores", isCorrect: false, feedback: "Navegadores são Chrome, Firefox, Edge — acessam a internet." }, { label: "Hardwares", isCorrect: false, feedback: "Errado! São programas (Softwares), não componentes físicos." }, { label: "Jogos", isCorrect: false, feedback: "São softwares de base, muito mais importantes que jogos!" }]
      },
      {
        question: "Qual dispositivo é de SAÍDA de dados?", icon: "🔊",
        options: [{ label: "Monitor", isCorrect: true, feedback: "Correto! O monitor exibe a imagem gerada pelo computador." }, { label: "Teclado", isCorrect: false, feedback: "O Teclado é de Entrada — você manda dados para o PC." }, { label: "Mouse", isCorrect: false, feedback: "O Mouse é de Entrada — você controla o cursor." }, { label: "Microfone", isCorrect: false, feedback: "O Microfone é Entrada — capta som do ambiente." }]
      },
      {
        question: "Para que serve o atalho ALT + TAB?", icon: "🔄",
        options: [{ label: "Alternar janelas", isCorrect: true, feedback: "Isso! Você navega entre todos os programas abertos." }, { label: "Fechar janelas", isCorrect: false, feedback: "Para fechar a janela ativa use ALT + F4." }, { label: "Salvar arquivo", isCorrect: false, feedback: "Para salvar use CTRL + S." }, { label: "Reiniciar PC", isCorrect: false, feedback: "Reiniciar não tem atalho padrão de teclado." }]
      },
      {
        question: "Qual destes é um Software de edição de textos?", icon: "📝",
        options: [{ label: "Word", isCorrect: true, feedback: "Correto! O Microsoft Word é o editor de textos mais popular." }, { label: "Excel", isCorrect: false, feedback: "Excel é para planilhas com cálculos e gráficos." }, { label: "Chrome", isCorrect: false, feedback: "Chrome é um navegador para acessar a internet." }, { label: "Spotify", isCorrect: false, feedback: "Spotify é um serviço de streaming de música." }]
      },
      {
        question: "A Memória RAM armazena dados:", icon: "🧠",
        options: [{ label: "Temporariamente", isCorrect: true, feedback: "Exato! Ao desligar o PC, tudo que estava na RAM some." }, { label: "Para sempre", isCorrect: false, feedback: "Armazenamento permanente é função do HD ou SSD." }, { label: "Na nuvem", isCorrect: false, feedback: "Nuvem é um servidor externo. A RAM é física e local." }, { label: "Somente vídeos", isCorrect: false, feedback: "A RAM armazena qualquer dado em uso, não só vídeos." }]
      },
      {
        question: "Atalho para SALVAR um arquivo (padrão Windows):", icon: "💾",
        options: [{ label: "CTRL + S", isCorrect: true, feedback: "Correto! S de Save — salve sempre com frequência!" }, { label: "CTRL + P", isCorrect: false, feedback: "CTRL+P abre o menu de Impressão (Print)." }, { label: "CTRL + O", isCorrect: false, feedback: "CTRL+O abre um arquivo existente (Open)." }, { label: "CTRL + N", isCorrect: false, feedback: "CTRL+N cria um novo arquivo em branco." }]
      },
      {
        question: "Onde ficam guardados os arquivos permanentemente?", icon: "💿",
        options: [{ label: "HD ou SSD", isCorrect: true, feedback: "Correto! HD e SSD são os discos de armazenamento permanente." }, { label: "RAM", isCorrect: false, feedback: "A RAM é volátil — perde os dados ao desligar." }, { label: "Processador", isCorrect: false, feedback: "O Processador processa, não guarda arquivos." }, { label: "Teclado", isCorrect: false, feedback: "O Teclado serve apenas para digitar." }]
      },
      {
        question: "Dispositivo que captura imagens físicas para o computador:", icon: "📷",
        options: [{ label: "Scanner", isCorrect: true, feedback: "Isso! O Scanner digitaliza documentos e fotos físicas." }, { label: "Impressora", isCorrect: false, feedback: "A Impressora faz o oposto: imprime do digital para o físico." }, { label: "Monitor", isCorrect: false, feedback: "O Monitor apenas exibe imagens, não as captura." }, { label: "Fone de Ouvido", isCorrect: false, feedback: "O Fone reproduz áudio, não captura imagens." }]
      },
      {
        question: "O que significa a sigla WWW?", icon: "🌐",
        options: [{ label: "World Wide Web", isCorrect: true, feedback: "Correto! A 'teia mundial' — base da internet que conhecemos." }, { label: "Wide World Web", isCorrect: false, feedback: "A ordem correta é World Wide Web." }, { label: "Web World Wide", isCorrect: false, feedback: "Não! A sigla segue a ordem: World Wide Web." }, { label: "Wireless Wide Web", isCorrect: false, feedback: "Wireless tem a ver com Wi-Fi, não com WWW." }]
      },
      {
        question: "Atalho para SELECIONAR TUDO em um documento:", icon: "📑",
        options: [{ label: "CTRL + A", isCorrect: true, feedback: "Correto! A de All (tudo) — seleciona todo o conteúdo." }, { label: "CTRL + T", isCorrect: false, feedback: "CTRL+T abre uma nova aba no navegador." }, { label: "CTRL + C", isCorrect: false, feedback: "CTRL+C é para Copiar o conteúdo selecionado." }, { label: "CTRL + D", isCorrect: false, feedback: "CTRL+D adiciona a página aos Favoritos no navegador." }]
      },
      {
        question: "O principal mecanismo de busca da internet é o:", icon: "🔍",
        options: [{ label: "Google", isCorrect: true, feedback: "Exato! O Google é o buscador mais usado do mundo." }, { label: "Facebook", isCorrect: false, feedback: "Facebook é uma rede social, não um buscador." }, { label: "WhatsApp", isCorrect: false, feedback: "WhatsApp é um aplicativo de mensagens." }, { label: "Instagram", isCorrect: false, feedback: "Instagram é uma rede social de fotos e vídeos." }]
      },
      {
        question: "Qual atalho FECHA a janela ativa?", icon: "❌",
        options: [{ label: "ALT + F4", isCorrect: true, feedback: "Correto! ALT+F4 fecha o programa em foco." }, { label: "ALT + TAB", isCorrect: false, feedback: "ALT+TAB alterna entre janelas abertas." }, { label: "CTRL + W", isCorrect: false, feedback: "CTRL+W fecha a aba atual, não a janela inteira." }, { label: "WIN + D", isCorrect: false, feedback: "WIN+D minimiza tudo e mostra a Área de Trabalho." }]
      },
      {
        question: "A peça que conecta todos os componentes do PC é a:", icon: "🔌",
        options: [{ label: "Placa-Mãe", isCorrect: true, feedback: "Exato! A Placa-Mãe é a espinha dorsal do computador." }, { label: "Fonte", isCorrect: false, feedback: "A Fonte de alimentação fornece energia elétrica." }, { label: "Processador", isCorrect: false, feedback: "O Processador é o cérebro, não o conector." }, { label: "Gabinete", isCorrect: false, feedback: "O Gabinete é apenas a caixa que abriga os componentes." }]
      }
    ];

    /* ══════════════════════════════════════════════════
       STATE
    ══════════════════════════════════════════════════ */
    let current = 0, totalPoints = 0, answered = false, gameOver = false;
    let timerInterval = null, timeLeft = 100;
    let isMultiplayer = false, sessaoId = null, jogadorIdMulti = null;
    let lastSyncIndex = -1, nomeUser = '';

    // Preenche o código da sala a partir da query string (?codigo=123456)
    const PRESET_SALA = new URLSearchParams(location.search).get('codigo') || '';

    // Power state for this player this round
    let activePower = null; // 'ice' | 'gloop' | null
    let iceCountdownInterval = null;
    let gloopActive = false;
    let pendingAnswer = false; // blocked while gloop active

    function shuffle(arr) { return [...arr].sort(() => Math.random() - 0.5); }

    /* ══════════════════════════════════════════════════
       INIT
    ══════════════════════════════════════════════════ */
    window.onload = async () => {
      const { value: mode } = await Swal.fire({
        title: '⚡ BEM-VINDO',
        html: '<p style="letter-spacing:1px;margin-bottom:0">ESCOLHA SEU MODO DE JOGO:</p>',
        showDenyButton: true,
        confirmButtonText: '🎯 SOLO',
        denyButtonText: '⚔️ DUELO',
        confirmButtonColor: '#5c6bc0',
        denyButtonColor: '#ff8c00',
        allowOutsideClick: false,
      });

      isMultiplayer = (mode === false); // deny = false

      const { value: form } = await Swal.fire({
        title: '👤 IDENTIFIQUE-SE',
        html: `
      <input id="swal-nome" class="swal2-input" placeholder="Seu nome completo" autocapitalize="words">
      <input id="swal-extra" class="swal2-input" placeholder="${isMultiplayer ? 'Código da Sala (6 dígitos)' : 'Sua Turma (ex: 1A)'}" value="${isMultiplayer ? PRESET_SALA : ''}">
    `,
        confirmButtonText: 'ENTRAR',
        confirmButtonColor: '#5c6bc0',
        allowOutsideClick: false,
        preConfirm: () => {
          const nome = document.getElementById('swal-nome').value.trim();
          const extra = document.getElementById('swal-extra').value.trim();
          if (nome.length < 2) { Swal.showValidationMessage('Digite seu nome!'); return false; }
          return { nome, extra };
        }
      });

      nomeUser = form.nome;

      if (isMultiplayer) {
        try {
          const res = await axios.post('api_player.php?action=entrar', { nome: form.nome, codigo: form.extra });
          if (res.data.sucesso) {
            sessaoId = res.data.sessao_id;
            jogadorIdMulti = res.data.jogador_id;
            startSyncPolling();
            // mostrar botão Desistir para o jogador
            const leaveBtn = document.getElementById('btn-leave');
            if (leaveBtn) { leaveBtn.style.display = 'inline-block'; leaveBtn.addEventListener('click', leaveSession); }
          } else {
            await Swal.fire('Erro', res.data.erro || 'Sala não encontrada.', 'error');
            location.reload();
          }
        } catch {
          await Swal.fire('Erro de conexão', 'Não foi possível entrar na sala.', 'error');
          location.reload();
        }
      } else {
        // SOLO
        ITEMS.forEach(it => { it._curOps = shuffle(it.options); });
        document.getElementById('splash-overlay').classList.add('hidden');
        renderQuestion();
      }
    };

    async function leaveSession() {
      const ok = await Swal.fire({ title: 'Desistir?', text: 'Você vai sair da sala e perderá sua participação.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ff1744' });
      if (!ok.isConfirmed) return;
      try {
        await axios.post('api_player.php?action=sair', { jogador_id: jogadorIdMulti, sessao_id: sessaoId });
        gameOver = true; // fará o polling encerrar
        await Swal.fire({ icon: 'success', title: 'Saída registrada', text: 'Você saiu da sala.', timer: 1200, showConfirmButton: false });
        location.reload();
      } catch (e) {
        console.error(e);
        Swal.fire('Erro', 'Não foi possível sair da sala.', 'error');
      }
    }

    /* ══════════════════════════════════════════════════
       MULTIPLAYER POLLING
    ══════════════════════════════════════════════════ */
    function startSyncPolling() {
      const poller = setInterval(async () => {
        if (gameOver) { clearInterval(poller); return; }
        try {
          const res = await axios.get(
            `api_player.php?action=status&sessao_id=${sessaoId}&jogador_id=${jogadorIdMulti}&last_index=${lastSyncIndex}`
          );
          const data = res.data;

          if (data.encerrada) {
            gameOver = true; clearInterval(poller);
            if (Swal.isVisible()) Swal.close();
            showFinalRanking(); return;
          }

          const remoteIdx = parseInt(data.sessao?.indice_pergunta_atual ?? -1);
          if (remoteIdx > lastSyncIndex) {
            lastSyncIndex = remoteIdx;
            current = remoteIdx;
            if (Swal.isVisible()) Swal.close();
            document.getElementById('splash-overlay').classList.add('hidden');

            // data.ataque    = 'ice' | 'gloop' | null
            // data.quem_atacou = nome do atacante | null
            await flowPerguntaCEP(data.ataque || null, data.quem_atacou || null);
          }
        } catch (e) { console.warn('Polling error', e); }
      }, 1500);
    }

    /* ══════════════════════════════════════════════════
       CEP FLOW — por rodada
    ══════════════════════════════════════════════════ */
    async function flowPerguntaCEP(ataqueRecebido, quemAtacou) {
      // Reset power state
      activePower = null;
      clearIceEffect();
      clearGloopEffect();

      hideQuestion();

      /* ── 1. FASE DE PODERES (8s) ── */
      await showPowerPhase();

      /* ── 2. REVELAÇÃO DO ATAQUE ── */
      if (ataqueRecebido) {
        await showAttackReveal(ataqueRecebido, quemAtacou);
      }

      /* ── 3. GET READY (5s) ── */
      await showGetReady();

      /* ── 4. PERGUNTA + EFEITO ── */
      renderQuestion(ataqueRecebido, quemAtacou);
    }

    /* ── Fase de Poderes ── */
    function showPowerPhase() {
      return new Promise(resolve => {
        let powerChosen = false;
        let fillEl;

        Swal.fire({
          title: '⚡ FASE DE PODERES',
          html: `
        <p style="color:var(--muted);font-size:.85rem;letter-spacing:1px;margin-bottom:1rem">Escolha um poder para lançar em um colega!</p>
        <button class="power-btn pbtn-ice"   onclick="window._choosePower('ice')">❄️ GELO &nbsp;<small style="opacity:.6;font-size:.7rem">— Congela os botões por 5s</small></button>
        <button class="power-btn pbtn-gloop" onclick="window._choosePower('gloop')">🟢 GOSMA &nbsp;<small style="opacity:.6;font-size:.7rem">— Cobre a tela até clicar</small></button>
        <button class="power-btn pbtn-skip"  onclick="window._choosePower(null)">⏭️ PASSAR &nbsp;<small style="opacity:.6;font-size:.7rem">— Sem poder desta vez</small></button>
        <div class="power-phase-bar" style="margin-top:1rem"><div class="power-phase-fill" id="ppfill" style="width:100%"></div></div>
      `,
          showConfirmButton: false,
          allowOutsideClick: false,
          didOpen: () => {
            fillEl = document.getElementById('ppfill');
            let pct = 100;
            const iv = setInterval(() => {
              pct -= 100 / 80; // 8s × 10fps
              if (fillEl) fillEl.style.width = Math.max(0, pct) + '%';
              if (pct <= 0) { clearInterval(iv); if (!powerChosen) { window._choosePower(null); } }
            }, 100);
          }
        });

        window._choosePower = async (tipo) => {
          if (powerChosen) return;
          powerChosen = true;

          if (tipo && isMultiplayer) {
            // Listar oponentes e escolher alvo
            Swal.update({ title: '🎯 ESCOLHA O ALVO', showConfirmButton: false });
            try {
              const res = await axios.get(
                `api_player.php?action=listar_oponentes&sessao_id=${sessaoId}&jogador_id=${jogadorIdMulti}`
              );
              const oponentes = res.data.jogadores || [];
              if (oponentes.length === 0) { Swal.close(); resolve(); return; }

              const targetHTML = oponentes.map(o =>
                `<button class="target-btn" onclick="window._launchAt('${o.id}','${tipo}','${o.nome}')">${o.nome.toUpperCase()}</button>`
              ).join('');
              Swal.update({ html: `<p style="color:var(--muted);margin-bottom:.8rem;font-size:.85rem">Quem vai receber o ${tipo === 'ice' ? '❄️ GELO' : '🟢 GOSMA'}?</p>${targetHTML}` });

              window._launchAt = async (alvoId, tipoP, nomeAlvo) => {
                await axios.post('api_player.php?action=atacar', {
                  alvo_id: alvoId, tipo: tipoP, meu_nome: nomeUser
                });
                Swal.fire({
                  icon: 'success', iconColor: tipoP === 'ice' ? '#00d4ff' : '#00e676',
                  title: tipoP === 'ice' ? '❄️ GELO LANÇADO!' : '🟢 GOSMA LANÇADA!',
                  html: `<b style="color:#fff">${nomeAlvo.toUpperCase()}</b> vai sofrer!`,
                  timer: 2000, showConfirmButton: false
                }).then(resolve);
              };
            } catch { Swal.close(); resolve(); }
          } else {
            Swal.close();
            resolve();
          }
        };
      });
    }

    /* ── Revelação do ataque recebido ── */
    function showAttackReveal(tipo, quemAtacou) {
      const attacker = (quemAtacou || 'ALGUÉM').toUpperCase();
      const isIce = tipo === 'ice';
      const icon = isIce ? '❄️' : '🟢';
      const name = isIce ? 'GELO' : 'GOSMA';
      const color = isIce ? '#00d4ff' : '#00e676';

      return Swal.fire({
        title: `${icon} ATAQUE RECEBIDO! ${icon}`,
        html: `
      <div style="text-align:center">
        <div style="font-size:3rem;margin:.5rem 0">${icon}</div>
        <div style="font-family:'Orbitron',sans-serif;font-size:1.4rem;color:${color};margin-bottom:.5rem">${name}!</div>
        <div style="font-size:1rem;color:rgba(220,232,248,.7)">
          <b style="color:#fff">${attacker}</b> lançou ${isIce ? 'Gelo' : 'Gosma'} em você!
        </div>
        <div style="margin-top:.8rem;font-size:.8rem;color:rgba(220,232,248,.45);letter-spacing:1px">
          ${isIce ? 'Seus botões ficarão congelados por 5 segundos!' : 'Você precisará limpar a gosma antes de responder!'}
        </div>
      </div>
    `,
        timer: 3500,
        showConfirmButton: false,
        allowOutsideClick: false,
      });
    }

    /* ── Get Ready countdown ── */
    function showGetReady() {
      return new Promise(resolve => {
        const screen = document.getElementById('get-ready-screen');
        const labelEl = document.getElementById('ready-label');
        const numEl = document.getElementById('get-ready-timer');
        labelEl.textContent = `RODADA ${current + 1}`;
        screen.classList.remove('hidden');

        let count = 3;
        numEl.textContent = count;

        const iv = setInterval(() => {
          count--;
          if (count <= 0) {
            clearInterval(iv);
            screen.classList.add('hidden');
            resolve();
          } else {
            numEl.textContent = count;
            // re-trigger animation
            numEl.style.animation = 'none';
            void numEl.offsetWidth;
            numEl.style.animation = 'readyPop 1s ease';
          }
        }, 1000);
      });
    }

    /* ── Hide question area ── */
    function hideQuestion() {
      document.getElementById('question-content').classList.add('hidden');
      document.getElementById('game-controls').classList.add('hidden');
      document.getElementById('attacker-toast').classList.remove('active', 'ice-toast', 'gloop-toast');
      document.getElementById('ice-countdown').classList.remove('active');
      document.getElementById('gloop-overlay').classList.remove('active');
    }

    /* ══════════════════════════════════════════════════
       RENDER QUESTION + APPLY POWER EFFECTS
    ══════════════════════════════════════════════════ */
    function renderQuestion(ataqueRecebido, quemAtacou) {
      if (current >= ITEMS.length) { if (!isMultiplayer) showFinalRanking(); return; }
      answered = false;
      pendingAnswer = false;
      gloopActive = false;

      const q = ITEMS[current];
      q._curOps = shuffle(q.options);

      document.getElementById('item-icon').textContent = q.icon;
      document.getElementById('item-name').textContent = q.question;
      document.getElementById('q-label').textContent = `Questão ${current + 1}/15`;
      document.getElementById('question-content').classList.remove('hidden');
      document.getElementById('game-controls').classList.remove('hidden');

      // Build buttons
      document.getElementById('btn-area').innerHTML = q._curOps.map((o, i) =>
        `<button class="btn-choice ${i % 2 === 0 ? 'btn-hw' : 'btn-sw'}" onclick="answer(${i})">${o.label}</button>`
      ).join('');

      // ── Apply received power effect BEFORE enabling buttons ──
      if (ataqueRecebido === 'ice') {
        applyIceEffect(quemAtacou);
      } else if (ataqueRecebido === 'gloop') {
        applyGloopEffect(quemAtacou);
        setButtons(true); // keep disabled until gloop cleared
      } else {
        setButtons(false);
      }

      startTimer();
    }

    /* ══════════════════════════════════════════════════
       ICE EFFECT ❄️
       Congela os botões por 5s — texto oculto, ❄️ aparece
    ══════════════════════════════════════════════════ */
    function applyIceEffect(quemAtacou) {
      const attacker = (quemAtacou || '').toUpperCase();

      // Attacker toast
      showAttackerToast('ice', attacker);

      // Apply ice CSS to all buttons
      document.querySelectorAll('.btn-choice').forEach(b => {
        b.classList.add('ice-effect');
        b.disabled = true;
      });

      // Show countdown badge
      const badge = document.getElementById('ice-countdown');
      const secEl = document.getElementById('ice-sec');
      badge.classList.add('active');
      let remaining = 5;
      secEl.textContent = remaining;

      clearInterval(iceCountdownInterval);
      iceCountdownInterval = setInterval(() => {
        remaining--;
        secEl.textContent = remaining;
        if (remaining <= 0) {
          clearIceEffect();
        }
      }, 1000);
    }

    function clearIceEffect() {
      clearInterval(iceCountdownInterval);
      document.getElementById('ice-countdown').classList.remove('active');
      document.querySelectorAll('.btn-choice').forEach(b => {
        b.classList.remove('ice-effect');
        if (!answered) b.disabled = false;
      });
    }

    /* ══════════════════════════════════════════════════
       GLOOP EFFECT 🟢
       Cobre a tela inteira com gosma — aluno clica para limpar
       Quanto mais demora, menos tempo sobra no cronômetro
    ══════════════════════════════════════════════════ */
    function applyGloopEffect(quemAtacou) {
      const attacker = (quemAtacou || '').toUpperCase();
      gloopActive = true;

      showAttackerToast('gloop', attacker);

      const overlay = document.getElementById('gloop-overlay');
      overlay.classList.add('active');
      setButtons(true); // blocked until cleaned
    }

    function cleanGloop() {
      if (!gloopActive) return;
      gloopActive = false;
      document.getElementById('gloop-overlay').classList.remove('active');
      if (!answered) setButtons(false);
    }

    function clearGloopEffect() {
      gloopActive = false;
      document.getElementById('gloop-overlay').classList.remove('active');
    }

    /* ── Attacker toast inside the card ── */
    function showAttackerToast(tipo, attacker) {
      const toast = document.getElementById('attacker-toast');
      toast.className = 'active ' + (tipo === 'ice' ? 'ice-toast' : 'gloop-toast');

      const icon = tipo === 'ice' ? '❄️' : '🟢';
      const label = tipo === 'ice' ? 'GELO' : 'GOSMA';
      toast.textContent = attacker
        ? `${icon} ${attacker} usou ${label} em você!`
        : `${icon} Você foi atingido por ${label}!`;

      // Auto-hide toast after 4s
      setTimeout(() => { toast.classList.remove('active'); }, 4000);
    }

    /* ══════════════════════════════════════════════════
       TIMER
    ══════════════════════════════════════════════════ */
    function startTimer() {
      clearInterval(timerInterval);
      timeLeft = 100;
      const fill = document.getElementById('timer-bar-fill');
      fill.style.width = '100%';
      // Re-trigger animation
      fill.style.animation = 'none';
      void fill.offsetWidth;
      fill.style.animation = 'timerColorShift 15s linear forwards';

      timerInterval = setInterval(() => {
        timeLeft -= 0.7;
        fill.style.width = Math.max(0, timeLeft) + '%';
        if (timeLeft <= 0) { clearInterval(timerInterval); answer(-1); }
      }, 100);
    }

    /* ══════════════════════════════════════════════════
       ANSWER
    ══════════════════════════════════════════════════ */
    async function answer(idx) {
      if (answered) return;
      // Block if gloop is still active
      if (gloopActive) return;

      answered = true;
      clearInterval(timerInterval);
      clearIceEffect();
      clearGloopEffect();
      setButtons(true);

      const q = ITEMS[current];
      const sel = idx === -1
        ? { isCorrect: false, feedback: '⏱️ Tempo esgotado! Tente ser mais rápido na próxima.' }
        : q._curOps[idx];

      const pts = sel.isCorrect ? Math.max(10, Math.round(timeLeft)) : 0;
      if (sel.isCorrect) totalPoints += pts;
      document.getElementById('hud-pts').textContent = totalPoints + ' pts';

      if (isMultiplayer) {
        try { await axios.post('api_player.php?action=responder', { jogador_id: jogadorIdMulti, pontos: pts }); }
        catch { /* silent */ }
      }

      await Swal.fire({
        icon: sel.isCorrect ? 'success' : 'error',
        iconColor: sel.isCorrect ? '#00e676' : '#ff1744',
        title: sel.isCorrect ? `+${pts} PONTOS!` : 'ERROU!',
        text: sel.feedback,
        confirmButtonText: isMultiplayer ? 'OK' : (current + 1 < ITEMS.length ? 'PRÓXIMA ›' : 'VER RESULTADO'),
        confirmButtonColor: '#5c6bc0',
        allowOutsideClick: false,
      });

      if (!isMultiplayer) {
        current++;
        renderQuestion();
      } else {
        showMiniRanking();
      }
    }

    /* ══════════════════════════════════════════════════
       MINI RANKING (MULTIPLAYER)
    ══════════════════════════════════════════════════ */
    async function showMiniRanking() {
      try {
        const res = await axios.get(`api_host.php?action=status&sessao_id=${sessaoId}`);
        const list = res.data.jogadores || [];
        const sorted = [...list].sort((a, b) => b.pontuacao - a.pontuacao);
        const pos = sorted.findIndex(j => j.nome === nomeUser) + 1;
        Swal.fire({
          title: '📊 POSIÇÃO ATUAL',
          html: `<div style="font-family:'Orbitron',sans-serif;font-size:4rem;color:var(--gold)">${pos}º</div><p style="letter-spacing:2px;font-weight:bold">${totalPoints} PONTOS</p>`,
          timer: 4000, showConfirmButton: false
        });
      } catch { /* silent */ }
    }

    /* ══════════════════════════════════════════════════
       FINAL RANKING
    ══════════════════════════════════════════════════ */
    async function showFinalRanking() {
      clearInterval(timerInterval);
      clearIceEffect();
      clearGloopEffect();

      let html;
      try {
        const res = await axios.get(`api_host.php?action=status&sessao_id=${sessaoId}`);
        const ranks = (res.data.jogadores || []).sort((a, b) => b.pontuacao - a.pontuacao);
        const colors = ['var(--gold)', '#c0c0c0', '#cd7f32'];
        html = ranks.map((r, i) => `
      <div style="display:flex;justify-content:space-between;align-items:center;
                  padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.07);
                  color:${colors[i] || '#fff'};font-weight:bold;">
        <span>${i === 0 ? '🥇' : i === 1 ? '🥈' : i === 2 ? '🥉' : `${i + 1}º`} ${r.nome.toUpperCase()}</span>
        <span style="font-family:'Orbitron',sans-serif">${r.pontuacao} pts</span>
      </div>`).join('');
      } catch {
        html = `<p>Você fez <strong>${totalPoints}</strong> pontos!</p>`;
      }

      await Swal.fire({
        title: '🏆 PÓDIO FINAL 🏆',
        html: html,
        confirmButtonText: '🔄 JOGAR NOVAMENTE',
        confirmButtonColor: '#5c6bc0',
        allowOutsideClick: false,
      });
      location.reload();
    }

    /* ══════════════════════════════════════════════════
       HELPERS
    ══════════════════════════════════════════════════ */
    function setButtons(disabled) {
      document.querySelectorAll('.btn-choice').forEach(b => b.disabled = disabled);
    }
  </script>
</body>

</html>