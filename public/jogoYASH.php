<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}

$modoJogo = $_POST['modo_jogo'] ?? 'classico';
$temaCartas = $_POST['card_theme'] ?? 'frutas';
$tamanhoTabuleiro = $_POST['tamanho_tabuleiro'] ?? '4x4';

$nomeJogador = $_SESSION['usuario_nome'] ?? 'Jogador';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo da Memória - YASH</title>
    
    <link rel="stylesheet" href="assets/css/jogoYASH.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon_io/site.webmanifest">
</head>
<body>
  <header>
    <img src="assets/img/logoYASH.png" alt="YASH Logo" class="logo">

    <div class="game-info">
      <div class="match-panel">
        <div class="match-row">
          <span class="label">Jogadas:</span>
          <span class="value moves">0</span>
        </div>
        <div class="match-row">
          <span class="label">Tabuleiro:</span>
          <span class="value board-config"><?= htmlspecialchars($tamanhoTabuleiro) ?></span>
        </div>
        <div class="match-row mode-row">
          <span class="label">Modo:</span>
          <div class="mode-toggle">
            <button class="mode-btn <?= $modoJogo == 'classico' ? 'active' : '' ?>">Clássica</button>
            <button class="mode-btn <?= $modoJogo == 'contra_tempo' ? 'active' : '' ?>">Contra o Tempo</button>
          </div>
        </div>
        <a href="historico.php" class="history-button">Histórico</a>
      </div>

      <div class="top-right">
        <div class="timer">00:00</div>

        <div class="cheat-config-row">
          <div class="cheat-buttons">
            <button class="cheat-button on">Ativar Trapaça</button>
            <button class="cheat-button off">Desativar Trapaça</button>
          </div>
          <input type="checkbox" id="settingsToggle" class="settings-toggle">
          <label for="settingsToggle" class="settings-button">⚙️</label>

          <div class="settings-menu">
            <a href="configYASH.php">Configurar Jogo</a>
            <a href="configuracoes_p.php">Perfil</a>
            <a href="logout.php">Sair</a>
          </div>
        </div>
      </div>
    </div>
  </header>

    <main>
      <div class="game-board">
         </div>
    </main>

    <div id="endGameModal" class="modal-overlay">
        <div class="modal-content">
            <h2 id="modalTitle">Parabéns!</h2>
            <p id="modalMessage">Você encontrou todos os pares.</p>
            <div class="modal-buttons">
                <a href="#" id="playAgainBtn" class="modal-btn">Jogar Novamente</a>
                <a href="configYASH.php" class="modal-btn secondary">Mudar Configurações</a>
            </div>
        </div>
    </div>

    <script>
        const CONFIG_JOGO = {
            modo: "<?= $modoJogo ?>",
            tema: "<?= $temaCartas ?>",
            tamanho: "<?= $tamanhoTabuleiro ?>",
            usuario: "<?= htmlspecialchars($nomeJogador) ?>"
        };
    </script>

    <script src="assets/js/jogoYASH.js"></script>
</body>
</html>