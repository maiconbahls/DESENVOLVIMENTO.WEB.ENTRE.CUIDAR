<?php
require 'config.php';

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->user_id)) {
    echo json_encode(["status" => "error", "message" => "ID do usuário não fornecido"]);
    exit;
}

$u = intval($data->user_id);

// Deleta a proposta para que o usuário possa refazer o diagnóstico
$stmt = $conn->prepare("DELETE FROM propostas WHERE user_id = ?");
$stmt->bind_param("i", $u);

if($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Proposta excluída. O cliente agora pode refazer o diagnóstico."]);
} else {
    echo json_encode(["status" => "error", "message" => "Erro ao excluir proposta."]);
}

$stmt->close();
$conn->close();
?>
