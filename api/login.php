<?php
require 'config.php';

$data = json_decode(file_get_contents("php://input"));
if (!isset($data->email) || !isset($data->password)) {
    echo json_encode(["status" => "error", "message" => "Informe seu e-mail e senha para entrar."]);
    exit;
}

$email = $data->email;
$password = $data->password;

$stmt = $conn->prepare("SELECT id, role, empresa_name, contato_name, password_hash FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $user = $res->fetch_assoc();
    
    // Verifica a senha no Hash
    if (password_verify($password, $user['password_hash'])) {
        echo json_encode([
            "status" => "success", 
            "message" => "Login aprovado!",
            "user" => [
                "id" => $user['id'],
                "role" => $user['role'],
                "empresa" => $user['empresa_name'],
                "contato" => $user['contato_name']
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Senha incorreta. Tente novamente."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Este e-mail não foi encontrado em nossa rede."]);
}

$stmt->close();
$conn->close();
?>
