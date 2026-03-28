<?php
require 'config.php';

$data = json_decode(file_get_contents("php://input"));
if (!isset($data->id)) {
    echo json_encode(["status" => "error", "message" => "ID não fornecido"]);
    exit;
}

$id = intval($data->id);

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $conn->query("DELETE FROM propostas WHERE user_id = $id");
    $conn->query("DELETE FROM messages WHERE user_id = $id");
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
