<?php
session_start();
require_once dirname(__DIR__) . '/config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$nomeUsuario = $_SESSION['usuario_nome'] ?? 'Jogador';

try {
    $sql = "SELECT dimensoes, modalidade, tempo, jogadas, resultado, data_partida 
            FROM partidas 
            WHERE usuario_id = :uid 
            ORDER BY data_partida DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':uid', $usuarioId);
    $stmt->execute();
    
    $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $historico = [];
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Partidas - YASH</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/configuracoes.css">
    
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon_io/site.webmanifest">
</head>

<body>
    <a href="configYASH.php" class="btn-voltar">← Voltar</a>

    <main class="container">
        <img src="assets/img/logoYASH.png" alt="Logo da YASH" style="width: 200px;">
        
        <section id="historico" style="display: block; padding-top: 1rem;">
            
            <h1>Histórico de <?= htmlspecialchars($nomeUsuario) ?></h1>
            
            <div class="table-responsive"> <table>
                    <thead>
                        <tr>
                            <th>Dimensões</th>
                            <th>Modalidade</th>
                            <th>Tempo Gasto</th>
                            <th>Jogadas</th>
                            <th>Resultado</th>
                            <th>Data/Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($historico) > 0): ?>
                            <?php foreach ($historico as $partida): ?>
                                <tr>
                                    <td><?= htmlspecialchars($partida['dimensoes']) ?></td>
                                    <td><?= htmlspecialchars($partida['modalidade']) ?></td>
                                    <td><?= htmlspecialchars($partida['tempo']) ?></td>
                                    <td><?= htmlspecialchars($partida['jogadas']) ?></td>
                                    
                                    <td style="color: <?= $partida['resultado'] == 'Vitória' ? 'green' : 'red' ?>; font-weight: bold;">
                                        <?= htmlspecialchars($partida['resultado']) ?>
                                    </td>
                                    
                                    <td><?= date('d/m/Y - H:i', strtotime($partida['data_partida'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px;">
                                    Nenhuma partida registrada ainda. <br>
                                    <a href="configYASH.php" style="color: var(--purple-light);">Jogue agora!</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>