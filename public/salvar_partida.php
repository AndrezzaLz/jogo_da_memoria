<?php
session_start();
require_once dirname(__DIR__) . '/config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);// Não autorizado
    echo json_encode(["erro" => "Usuário não logado"]);
    exit;
}
$json = file_get_contents('php://input');
$dados = json_decode($json, true);

if ($dados) {
    try {
        $sql = "INSERT INTO partidas (usuario_id, dimensoes, modalidade, tempo, jogadas, resultado) 
                VALUES (:uid, :dim, :mod, :tempo, :jog, :res)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':uid', $_SESSION['usuario_id']);
        $stmt->bindValue(':dim', $dados['dimensoes']);
        $stmt->bindValue(':mod', $dados['modalidade']);
        $stmt->bindValue(':tempo', $dados['tempo']);
        $stmt->bindValue(':jog', $dados['jogadas']);
        $stmt->bindValue(':res', $dados['resultado']);
        
        $stmt->execute();
        
        echo json_encode(["sucesso" => true]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro no banco: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["erro" => "Dados inválidos"]);
}
?>