<?php
require 'config.php';

$stmt = $conn->prepare("SELECT id, empresa_name, contato_name, email, created_at FROM users WHERE role = 'empresa' ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode(["status" => "success", "data" => $users]);

$stmt->close();
$conn->close();
?>
