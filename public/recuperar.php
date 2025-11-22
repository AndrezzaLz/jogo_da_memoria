<?php
// public/recuperar.php

require_once dirname(__DIR__) . '/config/database.php';

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    try {
        // Verifica se o e-mail existe na tabela de usuários
        $sql = "SELECT id, nome_completo FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            session_start();
            $_SESSION['email_recuperacao'] = $email;
            
            header("Location: recuperacao_enviada.php");
            exit;
        } else {
            $erro = "E-mail não encontrado em nossa base de dados.";
        }

    } catch (PDOException $e) {
        $erro = "Erro ao processar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - YASH</title>
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon_io/site.webmanifest">
</head>

<body>

    <a href="login.php" class="btn-voltar">← Voltar</a>

    <main class="container">

        <img src="assets/img/logoYASH.png" alt="Logo da YASH">

        <h1>Recuperar Senha</h1>

        <p class="form-instructions">
            Insira seu e-mail cadastrado para recuperar sua senha.
        </p>

        <?php if(!empty($erro)): ?>
            <div style="background-color: #ffcccc; color: #cc0000; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                <?= $erro; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="login-form">

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="seuemail@exemplo.com" required>

            <button type="submit">Enviar</button>
        </form>
    </main>
</body>
</html>