<?php
require 'config.php';

$data = json_decode(file_get_contents("php://input"));
if(!isset($data->user_id) || !isset($data->valor)) {
    echo json_encode(["status" => "error", "message" => "Dados inválidos"]);
    exit;
}

// Ensure columns exist and have the correct type for formatted strings
try { $conn->query("ALTER TABLE propostas ADD COLUMN orcamento_produto VARCHAR(255) NULL"); } catch(Exception $e) {}
try { $conn->query("ALTER TABLE propostas MODIFY COLUMN orcamento_valor VARCHAR(255) NULL"); } catch(Exception $e) {}
try { $conn->query("ALTER TABLE propostas ADD COLUMN orcamento_valor VARCHAR(255) NULL"); } catch(Exception $e) {}
try { $conn->query("ALTER TABLE propostas ADD COLUMN orcamento_detalhes TEXT NULL"); } catch(Exception $e) {}

$u = $data->user_id;
$p = $data->produto;
$v = $data->valor;
$d = $data->detalhes;

$check = $conn->prepare("SELECT id FROM propostas WHERE user_id = ?");
$check->bind_param("i", $u);
$check->execute();
$res = $check->get_result();

if($res->num_rows > 0) {
    $stmt = $conn->prepare("UPDATE propostas SET orcamento_produto = ?, orcamento_valor = ?, orcamento_detalhes = ?, status = 'Respondido' WHERE user_id = ?");
    $stmt->bind_param("sssi", $p, $v, $d, $u);
    $stmt->execute();
} else {
    $stmt2 = $conn->prepare("INSERT INTO propostas (user_id, status, orcamento_produto, orcamento_valor, orcamento_detalhes) VALUES (?, 'Respondido', ?, ?, ?)");
    $stmt2->bind_param("isss", $u, $p, $v, $d);
    $stmt2->execute();
}

echo json_encode(["status" => "success"]);
