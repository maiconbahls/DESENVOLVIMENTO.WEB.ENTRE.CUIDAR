<?php
require 'config.php';

$uid = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if(!$uid) exit;

$stmt = $conn->prepare("SELECT * FROM propostas WHERE user_id = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$res = $stmt->get_result();

if($prop = $res->fetch_assoc()) {
    echo json_encode(["status" => "success", "data" => $prop]);
} else {
    echo json_encode(["status" => "empty"]);
}
