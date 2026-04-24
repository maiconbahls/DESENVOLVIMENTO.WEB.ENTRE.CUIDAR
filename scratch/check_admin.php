<?php
require 'api/config.php';

$email = 'adm@entrecuidar.com';
$stmt = $conn->prepare("SELECT id, role, email FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $user = $res->fetch_assoc();
    echo "User found: " . print_r($user, true);
} else {
    echo "User NOT found: " . $email;
}

$stmt->close();
$conn->close();
?>
