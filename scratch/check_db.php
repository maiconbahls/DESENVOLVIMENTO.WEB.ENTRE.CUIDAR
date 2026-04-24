<?php
require 'config.php';
$res = $conn->query("SELECT role, COUNT(*) as total FROM users GROUP BY role");
$stats = [];
while($row = $res->fetch_assoc()) {
    $stats[] = $row;
}
echo json_encode($stats);
?>
