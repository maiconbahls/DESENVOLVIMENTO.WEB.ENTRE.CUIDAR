<?php
require 'config.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->user_id)) {
    echo json_encode(["status" => "error", "message" => "Usuário não identificado."]);
    exit;
}

$user_id = intval($data->user_id);
$produto = isset($data->produto) ? $data->produto : '';
$desafio = isset($data->desafio) ? $data->desafio : '';
$area = isset($data->area) ? $data->area : '';
$prazo = isset($data->prazo) ? $data->prazo : '';
$detalhes = isset($data->detalhes) ? $data->detalhes : '';

// Concatenamos os detalhes em uma "pergunta 1" para aparecer no admin como diagnóstico inicial
$q1 = "Produto: " . $produto;
$q2 = "Desafio/Detalhes: " . ($produto == 'essencial' ? $desafio : $detalhes);
$q3 = "Área: " . $area;
$q4 = "Prazo: " . $prazo;
$q5 = "Solicitação inicial via formulário rápido.";

$stmt = $conn->prepare("INSERT INTO propostas (user_id, q1, q2, q3, q4, q5, status) VALUES (?, ?, ?, ?, ?, ?, 'Pendente Avaliação')");
$stmt->bind_param("isssss", $user_id, $q1, $q2, $q3, $q4, $q5);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
