<?php
require 'config.php';

$data = json_decode(file_get_contents("php://input"));
if (!isset($data->user_id)) {
    echo json_encode(["status" => "error", "message" => "Usuário não identificado. Faça login novamente."]);
    exit;
}

$user_id = intval($data->user_id);

// Verifica se o usuário ainda existe no banco
$checkUser = $conn->prepare("SELECT id FROM users WHERE id = ?");
$checkUser->bind_param("i", $user_id);
$checkUser->execute();
if ($checkUser->get_result()->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Sessão inválida. Este usuário foi removido do sistema."]);
    exit;
}

$q1 = isset($data->q1) ? $data->q1 : '';
$q2 = isset($data->q2) ? $data->q2 : '';
$q3 = isset($data->q3) ? $data->q3 : '';
$q4 = isset($data->q4) ? implode(', ', $data->q4) : '';
$q5 = isset($data->q5) ? $data->q5 : '';

$stmt = $conn->prepare("INSERT INTO propostas (user_id, q1, q2, q3, q4, q5, status) VALUES (?, ?, ?, ?, ?, ?, 'Pendente Avaliação')");
$stmt->bind_param("isssss", $user_id, $q1, $q2, $q3, $q4, $q5);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Proposta enviada com sucesso!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Erro ao salvar proposta: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
