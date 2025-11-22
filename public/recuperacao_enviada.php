<?php
session_start();
$emailEnviado = isset($_SESSION['email_recuperacao']) ? $_SESSION['email_recuperacao'] : "seu e-mail";

// Limpa a sessão para não ficar guardando lixo
unset($_SESSION['email_recuperacao']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instruções Enviadas - YASH</title>
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon_io/site.webmanifest">
</head>

<body>
    <main class="container">

        <img src="assets/img/logoYASH.png" alt="Logo da YASH">

        <h1>E-mail enviado!</h1>

        <p>Se o e-mail <strong><?= htmlspecialchars($emailEnviado) ?></strong> estiver cadastrado, você receberá um link para redefinir sua senha em instantes.</p>
        
        <p style="font-size: 0.9em; color: #666; margin-top: 10px;">
            (Como este é um ambiente de teste local, foi apenas feito a verificação do email).
        </p>

        <a href="login.php" class="forgot-link" style="text-align: center; display: block; margin-top: 2rem;">
            Voltar para a página de Login
        </a>
    </main>
</body>
</html>