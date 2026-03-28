<?php
require 'config.php';

$data = json_decode(file_get_contents("php://input"));
if(!isset($data->user_id) || !isset($data->valor)) {
    echo json_encode(["status" => "error", "message" => "Dados inválidos"]);
    exit;
}

// Failsafe to ensure columns exist
$conn->query("ALTER TABLE propostas ADD COLUMN orcamento_produto VARCHAR(255) NULL");
$conn->query("ALTER TABLE propostas ADD COLUMN orcamento_valor VARCHAR(255) NULL");
$conn->query("ALTER TABLE propostas ADD COLUMN orcamento_detalhes TEXT NULL");

$u = $data->user_id;
$p = $data->produto;
$v = $data->valor;
$d = $data->detalhes;

$stmt = $conn->prepare("UPDATE propostas SET orcamento_produto = ?, orcamento_valor = ?, orcamento_detalhes = ?, status = 'Respondido' WHERE user_id = ?");
$stmt->bind_param("sssi", $p, $v, $d, $u);
$stmt->execute();

if($stmt->affected_rows == 0) {
    // It means the user never even submitted the diagnostic form. Let's create an empty one with the budget.
    $stmt2 = $conn->prepare("INSERT INTO propostas (user_id, status, orcamento_produto, orcamento_valor, orcamento_detalhes) VALUES (?, 'Respondido', ?, ?, ?)");
    $stmt2->bind_param("isss", $u, $p, $v, $d);
    $stmt2->execute();
}

echo json_encode(["status" => "success"]);
?>
