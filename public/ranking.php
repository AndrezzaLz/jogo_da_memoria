<?php
session_start();
require_once dirname(__DIR__) . '/config/database.php';

try {
    
    $sql = "SELECT u.usuario, p.dimensoes, p.modalidade, p.tempo, p.jogadas, p.data_partida 
            FROM partidas p
            JOIN usuarios u ON p.usuario_id = u.id
            WHERE p.resultado = 'Vitória'
            ORDER BY p.dimensoes DESC, p.jogadas ASC
            LIMIT 10";

    $stmt = $pdo->query($sql);
    $ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $ranking = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking Global - YASH</title>
    
    <link rel="stylesheet" href="assets/css/style.css">
    
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon_io/site.webmanifest">
    
    <style>
        /* Destaque para o Top 3 */
        tr:nth-child(1) td { color: gold; font-weight: bold; }       
        tr:nth-child(2) td { color: silver; font-weight: bold; }     
        tr:nth-child(3) td { color: #cd7f32; font-weight: bold; }    
    </style>
</head>

<body>
    <a href="configYASH.php" class="btn-voltar">← Voltar</a>

    <div class="container">
        <img src="assets/img/logoYASH.png" alt="YASH">
        <h1>Ranking Global</h1>
        <p style="text-align: center; color: #000; margin-bottom: 15px; font-size: 0.9rem;">
            Top 10 Melhores Jogadores<br>(Prioridade: Maior Tabuleiro > Menos Jogadas)
        </p>

        <div id="historico" class="conteudo-aba">
            <div class="container-ranking">
                <table>
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th>Jogador</th>
                            <th>Dimensões</th>
                            <th>Jogadas</th> <th>Tempo</th>
                            <th>Modalidade</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($ranking) > 0): ?>
                            <?php 
                                $posicao = 1; 
                                foreach ($ranking as $jogo): 
                            ?>
                                <tr>
                                    <td>#<?= $posicao++ ?></td>
                                    <td><?= htmlspecialchars($jogo['usuario']) ?></td>
                                    
                                    <td style="font-weight: bold;"><?= htmlspecialchars($jogo['dimensoes']) ?></td>
                                    
                                    <td><?= htmlspecialchars($jogo['jogadas']) ?></td>
                                    
                                    <td><?= htmlspecialchars($jogo['tempo']) ?></td>
                                    <td><?= htmlspecialchars($jogo['modalidade']) ?></td>
                                    <td><?= date('d/m', strtotime($jogo['data_partida'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 20px;">
                                    Ainda não há vitórias registradas no sistema.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>