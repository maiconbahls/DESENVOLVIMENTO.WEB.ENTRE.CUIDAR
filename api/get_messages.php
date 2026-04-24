<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json');

require 'config.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_GET['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Obrigatório user_id"]);
    exit;
}
$user_id = intval($_GET['user_id']);

try {
    $stmt = $conn->prepare("SELECT * FROM messages WHERE user_id = ? ORDER BY created_at ASC");
    if (!$stmt) {
        // Fallback caso a coluna ainda não tenha sido renomeada pelo send_message.php
        $stmt = $conn->prepare("SELECT id, user_id, mensagem as message, sent_at as created_at FROM messages WHERE user_id = ? ORDER BY sent_at ASC");
    }
    
    if (!$stmt) {
        throw new Exception("Erro no prepare: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $messages = [];

    while ($row = $res->fetch_assoc()) {
        $messages[] = $row;
    }

    echo json_encode(["status" => "success", "data" => $messages]);
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

$conn->close();
