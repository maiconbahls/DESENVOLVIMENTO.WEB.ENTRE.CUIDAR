<?php
require 'config.php';

$sql = "SELECT u.id, u.empresa_name, u.contato_name, u.email, u.created_at, 
               p.id as proposta_id, p.status as proposta_status 
        FROM users u 
        LEFT JOIN propostas p ON u.id = p.user_id 
        WHERE u.role = 'empresa' 
        ORDER BY u.id DESC";

$stmt = $conn->prepare($sql);
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
