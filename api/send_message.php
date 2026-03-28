<?php
require 'config.php';

$data = json_decode(file_get_contents("php://input"));
if (!isset($data->user_id) || !isset($data->message)) {
    echo json_encode(["status" => "error", "message" => "Dados inválidos."]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO messages (user_id, message, is_admin) VALUES (?, ?, ?)");
$is_admin = isset($data->is_admin) ? $data->is_admin : 0;
$stmt->bind_param("isi", $data->user_id, $data->message, $is_admin);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
