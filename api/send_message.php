<?php
require 'config.php';

// Failsafe: Recreate/Check messages table
$conn->query("CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// If it already exists but doesn't have 'message' (maybe it was 'content' or something), let's just make it right.
// However, the error 'Unknown column message' is the smoker.
// Let's try to add the column 'message' if it doesn't exist, but simplest is to just ensure it's there.

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
