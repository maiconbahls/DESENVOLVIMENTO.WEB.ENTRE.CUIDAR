<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

require 'config.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Busca as últimas mensagens de cada usuário para listar na caixa de entrada
    // Tenta primeiro com o padrão novo (created_at)
    $sql = "SELECT m.*, u.empresa_name 
            FROM messages m
            JOIN users u ON m.user_id = u.id
            WHERE m.id IN (
                SELECT MAX(id) FROM messages GROUP BY user_id
            )
            ORDER BY m.created_at DESC";

    $result = $conn->query($sql);
    
    if (!$result) {
        // Fallback para padrão antigo (sent_at)
        $sql = "SELECT m.id, m.user_id, m.mensagem as message, m.sent_at as created_at, u.empresa_name 
                FROM messages m
                JOIN users u ON m.user_id = u.id
                WHERE m.id IN (
                    SELECT MAX(id) FROM messages GROUP BY user_id
                )
                ORDER BY m.sent_at DESC";
        $result = $conn->query($sql);
    }

    if (!$result) {
        throw new Exception("Erro na consulta: " . $conn->error);
    }

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    echo json_encode(["status" => "success", "data" => $messages]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

$conn->close();
