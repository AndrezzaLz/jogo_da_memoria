<?php
require_once dirname(__DIR__) . '/config/database.php';

$erro = "";
$sucesso = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    //Recebe os dados do formulário
    $nome = $_POST['fullName'];
    $nascimento = $_POST['dateBirth'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telephone'];
    $email = $_POST['email'];
    $usuario = $_POST['username'];
    $senha = $_POST['password'];
    $confirmarSenha = $_POST['confirmPassword'];

    if ($senha !== $confirmarSenha) {
        $erro = "As senhas não coincidem!";
    } else {
        try {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (nome_completo, data_nascimento, cpf, telefone, email, usuario, senha) 
                    VALUES (:nome, :nasc, :cpf, :tel, :email, :user, :senha)";
            
            $stmt = $pdo->prepare($sql);
            
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':nasc', $nascimento);
            $stmt->bindValue(':cpf', $cpf);
            $stmt->bindValue(':tel', $telefone);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':user', $usuario);
            $stmt->bindValue(':senha', $senhaHash);
            
            $stmt->execute();

            // Se chegou aqui, deu tudo certo!
            $sucesso = "Cadastro realizado com sucesso! Redirecionando para o login...";
            header("refresh:2;url=login.php"); // Redireciona automático

        } catch (PDOException $e) {
            //verifica se o erro é de duplicidade (código 23000 no SQL)
            if ($e->getCode() == 23000) {
                $erro = "Erro: Usuário, CPF ou E-mail já cadastrados!";
            } else {
                $erro = "Erro no sistema: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - YASH</title>
    
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon_io/site.webmanifest">
</head>

<body>
    <a href="login.php" class="btn-voltar">← Voltar</a>

    <div class="container">
        <img src="assets/img/logoYASH.png" alt="YASH">
        
        <?php if(!empty($erro)): ?>
            <div style="background-color: #ffcccc; color: #cc0000; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                <?= $erro; ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($sucesso)): ?>
            <div style="background-color: #ccffcc; color: #006600; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                <?= $sucesso; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="register-form">

            <div class="form-grid">
                <div class="form-group">
                    <label for="fullName">Nome Completo</label>
                    <input type="text" id="fullName" name="fullName" required>
                </div>

                <div class="form-group">
                    <label for="dateBirth">Data de Nascimento</label>
                    <input type="date" id="dateBirth" name="dateBirth" required>
                </div>

                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" placeholder="000.000.000-00" required>
                </div>

                <div class="form-group">
                    <label for="telephone">Telefone</label>
                    <input type="tel" id="telephone" name="telephone" pattern="\(\d{2}\) \d{4,5}-\d{4}" placeholder="(00) 00000-0000" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="username">Nome de Usuário</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirmar Senha</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
            </div>
            <button type="submit" class="register-submit">Cadastrar</button>
        </form>
    </div>
</body>
</html>