<?php
require 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$data = json_decode(file_get_contents("php://input"));
if (!isset($data->user_id) || !isset($data->message)) {
    echo json_encode(["status" => "error", "message" => "Dados inválidos."]);
    exit;
}

$u = $data->user_id;
$m = $data->message;
$is_admin = isset($data->is_admin) ? $data->is_admin : 0;

$stmt = $conn->prepare("INSERT INTO messages (user_id, message, is_admin) VALUES (?, ?, ?)");
if(!$stmt) {
    echo json_encode(["status" => "error", "message" => "Erro no banco. A tabela 'messages' existe? Rode setup_messages.php. Detalhe: " . $conn->error]);
    exit;
}
$stmt->bind_param("isi", $u, $m, $is_admin);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
