<?php
session_start();
require_once dirname(__DIR__) . '/config/database.php';

if (isset($_SESSION['usuario_id'])) {
    header("Location: configYASH.php");
    exit;
}

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $login = $_POST['username'];
    $senha = $_POST['password'];

    try {
        $sql = "SELECT id, nome_completo, usuario, senha FROM usuarios WHERE usuario = :login OR email = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':login', $login);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // password_verify checa se a senha digitada bate com o código criptografado (é nativo do php)
        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['nome_completo'];
            $_SESSION['usuario_login'] = $user['usuario'];
            
            header("Location: configYASH.php");
            exit;
        } else {
            $erro = "Usuário ou senha incorretos!";
        }

    } catch (PDOException $e) {
        $erro = "Erro ao conectar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - YASH</title>
    
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon_io/site.webmanifest">
</head>
<body>
    <div class="container">
        
        <img src="assets/img/logoYASH.png" alt="YASH">
        
        <?php if(!empty($erro)): ?>
            <div style="background-color: #ffcccc; color: #cc0000; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                <?= $erro; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="login-form">
            <label for="username">Usuário ou E-mail</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Senha</label>
            <input type="password" id="password" name="password" required>
            
            <a href="recuperar.php" class="forgot-link">Esqueceu a senha?</a>

            <button type="submit">Entrar</button>
        </form>
    </div>

    <div class="register-box">
        <p>Não tem conta?
            <a href="cadastro.php" class="register-text">Cadastre-se</a>
        </p>
    </div>
</body>
</html>