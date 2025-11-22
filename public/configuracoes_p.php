<?php
session_start();
require_once dirname(__DIR__) . '/config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$mensagem = "";
$tipoMensagem = ""; // 'sucesso' ou 'erro'

//Atualiza os dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoNome = $_POST['fullName'];
    $novoTelefone = $_POST['telephone'];
    $novoEmail = $_POST['email'];
    
    $senhaAtual = $_POST['password'];
    $novaSenha = $_POST['newPassword'];

    try {
        $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = :id");
        $stmt->bindValue(':id', $usuarioId);
        $stmt->execute();
        $dadosAtuais = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($senhaAtual, $dadosAtuais['senha'])) {
            if (!empty($novaSenha)) {
                $hashNovaSenha = password_hash($novaSenha, PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET nome_completo = :nome, telefone = :tel, email = :email, senha = :senha WHERE id = :id";
                $stmtUpdate = $pdo->prepare($sql);
                $stmtUpdate->bindValue(':senha', $hashNovaSenha);
            } else {
                $sql = "UPDATE usuarios SET nome_completo = :nome, telefone = :tel, email = :email WHERE id = :id";
                $stmtUpdate = $pdo->prepare($sql);
            }

            $stmtUpdate->bindValue(':nome', $novoNome);
            $stmtUpdate->bindValue(':tel', $novoTelefone);
            $stmtUpdate->bindValue(':email', $novoEmail);
            $stmtUpdate->bindValue(':id', $usuarioId);
            
            if ($stmtUpdate->execute()) {
                $mensagem = "Dados atualizados com sucesso!";
                $tipoMensagem = "sucesso";
                $_SESSION['usuario_nome'] = $novoNome;
            }
        } else {
            $mensagem = "Senha atual incorreta. As alterações não foram salvas.";
            $tipoMensagem = "erro";
        }

    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar: " . $e->getMessage();
        $tipoMensagem = "erro";
    }
}
$stmt = $pdo->prepare("SELECT nome_completo, data_nascimento, cpf, telefone, email, usuario FROM usuarios WHERE id = :id");
$stmt->bindValue(':id', $usuarioId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Nota: Precisamos criar a tabela 'partidas' no futuro
try {
    $sqlHist = "SELECT dimensoes, modalidade, tempo, jogadas, resultado, data_partida 
                FROM partidas 
                WHERE usuario_id = :uid 
                ORDER BY data_partida DESC LIMIT 10";
    $stmtHist = $pdo->prepare($sqlHist);
    $stmtHist->bindValue(':uid', $usuarioId);
    $stmtHist->execute();
    $historico = $stmtHist->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $historico = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - YASH</title>

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
        <h1>Configurações Pessoais</h1>

        <?php if (!empty($mensagem)): ?>
            <div style="padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; 
                background-color: <?= $tipoMensagem == 'sucesso' ? '#d4edda' : '#f8d7da' ?>; 
                color: <?= $tipoMensagem == 'sucesso' ? '#155724' : '#721c24' ?>;">
                <?= $mensagem; ?>
            </div>
        <?php endif; ?>

        <div class="abas-container">
            <input type="radio" name="abas" id="tab-info" checked>
            <input type="radio" name="abas" id="tab-hist">

            <nav class="abas-navegacao">
                <label for="tab-info" class="aba">Informações Pessoais</label>
                <label for="tab-hist" class="aba">Histórico do Jogador</label>
            </nav>

            <div class="conteudo-container">
                <section id="info-pessoais" class="conteudo-aba">
                    <h2>Informações Pessoais</h2>

                    <form class="register-form" method="POST" action="">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="fullName">Nome Completo</label>
                                <input type="text" id="fullName" name="fullName" value="<?= htmlspecialchars($user['nome_completo']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="dateBirth">Data de Nascimento</label>
                                <input type="text" id="dateBirth" name="dateBirth" value="<?= htmlspecialchars(date('d/m/Y', strtotime($user['data_nascimento']))) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="cpf">CPF</label>
                                <input type="text" id="cpf" name="cpf" value="<?= htmlspecialchars($user['cpf']) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="telephone">Telefone</label>
                                <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars($user['telefone']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="username">Nome de Usuário</label>
                                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['usuario']) ?>" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="password" style="color: #d9534f;">Senha Atual (Obrigatória para alterar dados)</label>
                                <input type="password" id="password" name="password" required placeholder="Digite sua senha atual">
                            </div>
                            <div class="form-group">
                                <label for="newPassword">Nova Senha (Opcional)</label>
                                <input type="password" id="newPassword" name="newPassword" placeholder="Deixe em branco para manter a mesma">
                            </div>
                        </div>
                        <button type="submit" class="register-submit">Salvar Alterações</button>
                    </form>
                </section>
                
                <section id="historico" class="conteudo-aba">
                    <h2>Seu Histórico de Partidas</h2>
                    
                    <div class="tabela-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Dimensões</th>
                                    <th>Modalidade</th>
                                    <th>Tempo</th>
                                    <th>Jogadas</th>
                                    <th>Resultado</th>
                                    <th>Data</th>
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
                                            <td><?= htmlspecialchars($partida['resultado']) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($partida['data_partida'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align:center">Você ainda não jogou nenhuma partida.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>