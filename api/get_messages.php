<?php
error_reporting(0);
ini_set('display_errors', 0);
require 'config.php';

if (!isset($_GET['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Obrigatório user_id"]);
    exit;
}

$user_id = intval($_GET['user_id']);

$stmt = $conn->prepare("SELECT * FROM messages WHERE user_id = ? ORDER BY created_at ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$messages = [];

while ($row = $res->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode(["status" => "success", "data" => $messages]);

$stmt->close();
$conn->close();
?>
