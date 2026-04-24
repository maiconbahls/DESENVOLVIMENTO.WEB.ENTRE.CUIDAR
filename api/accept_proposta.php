<?php
require 'config.php';

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->user_id)) {
    echo json_encode(["status" => "error", "message" => "ID do usuário não fornecido"]);
    exit;
}

$u = intval($data->user_id);

$stmt = $conn->prepare("UPDATE propostas SET status = 'Aceito' WHERE user_id = ?");
$stmt->bind_param("i", $u);

if($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Proposta aceita com sucesso!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Erro ao atualizar banco de dados."]);
}

$stmt->close();
$conn->close();
?>
