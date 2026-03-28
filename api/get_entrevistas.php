<?php
require 'config.php';

$sql = "SELECT * FROM entrevistas_completas ORDER BY created_at DESC";
$res = $conn->query($sql);

$data = [];
while($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(["status" => "success", "data" => $data]);
?>
