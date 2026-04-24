<?php
require 'config.php';

$data = json_decode(file_get_contents("php://input"));
if (!isset($data->email) || !isset($data->password)) {
    echo json_encode(["status" => "error", "message" => "Dados incompletos. Preencha todos os campos."]);
    exit;
}

$empresa = isset($data->empresa) ? $data->empresa : 'Não informado';
$contato = isset($data->contato) ? $data->contato : 'Não informado';
$email = $data->email;
$password = $data->password;

// Verifica se o email já existe
$check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
if ($check_stmt->get_result()->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Este e-mail já está cadastrado em nosso sistema."]);
    $check_stmt->close();
    exit;
}
$check_stmt->close();

$produto = isset($data->produto_interesse) ? $data->produto_interesse : 'Não informado';

// Criptografa a senha com Hash Forte
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (role, empresa_name, contato_name, email, password_hash, produto_interesse) VALUES ('empresa', ?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $empresa, $contato, $email, $hash, $produto);

if ($stmt->execute()) {
    $user_id = $stmt->insert_id;
    echo json_encode([
        "status" => "success", 
        "message" => "Cadastro realizado com sucesso!", 
        "user" => [
            "id" => $user_id,
            "role" => "empresa",
            "empresa" => $empresa
        ]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Erro interno no servidor de banco de dados."]);
}

$stmt->close();
$conn->close();
?>
