<?php
error_reporting(0);
ini_set('display_errors', 0);
require 'config.php';

// Failsafe: Recreate/Check messages table
$conn->query("CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Garantir que a coluna 'message' existe (caso tenha sido criada com outro nome antes)
$res = $conn->query("SHOW COLUMNS FROM messages LIKE 'message'");
if($res->num_rows == 0) {
    $conn->query("ALTER TABLE messages ADD COLUMN message TEXT NOT NULL AFTER user_id");
}

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
    echo json_encode(["status" => "error", "message" => "Erro no prepare: " . $conn->error]);
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
