<?php
require 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if(!$id) exit;

$stmt = $conn->prepare("SELECT * FROM entrevistas_completas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if($row = $res->fetch_assoc()) {
    echo json_encode(["status" => "success", "data" => $row]);
} else {
    echo json_encode(["status" => "error", "message" => "Não encontrado"]);
}
?>
