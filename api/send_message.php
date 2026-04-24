<?php
// Desativar saída de erros para o navegador para não quebrar o JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Garantir que a resposta seja sempre JSON
header('Content-Type: application/json');

require 'config.php';

// Ativar exceções para erros de MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Failsafe: Garantir que a tabela e as colunas corretas existam
    $conn->query("CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        message TEXT NOT NULL,
        is_admin TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Verificar se a coluna é 'message' ou 'mensagem' (caso tenha vindo do setup.php antigo)
    $res = $conn->query("SHOW COLUMNS FROM messages LIKE 'message'");
    if($res->num_rows == 0) {
        // Se não tem 'message', talvez tenha 'mensagem'?
        $res2 = $conn->query("SHOW COLUMNS FROM messages LIKE 'mensagem'");
        if($res2->num_rows > 0) {
            $conn->query("ALTER TABLE messages CHANGE mensagem message TEXT NOT NULL");
        } else {
            $conn->query("ALTER TABLE messages ADD COLUMN message TEXT NOT NULL AFTER user_id");
        }
    }

    // Verificar se tem 'is_admin'
    $res = $conn->query("SHOW COLUMNS FROM messages LIKE 'is_admin'");
    if($res->num_rows == 0) {
        $conn->query("ALTER TABLE messages ADD COLUMN is_admin TINYINT(1) DEFAULT 0 AFTER message");
    }

    // Verificar se tem 'created_at' ou 'sent_at'
    $res = $conn->query("SHOW COLUMNS FROM messages LIKE 'created_at'");
    if($res->num_rows == 0) {
        $res2 = $conn->query("SHOW COLUMNS FROM messages LIKE 'sent_at'");
        if($res2->num_rows > 0) {
            $conn->query("ALTER TABLE messages CHANGE sent_at created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        } else {
            $conn->query("ALTER TABLE messages ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        }
    }

    $json = file_get_contents("php://input");
    $data = json_decode($json);

    if (!$data || !isset($data->user_id) || !isset($data->message)) {
        echo json_encode(["status" => "error", "message" => "Dados inválidos ou incompletos."]);
        exit;
    }

    $u = intval($data->user_id);
    $m = $data->message;
    $is_admin = isset($data->is_admin) ? intval($data->is_admin) : 0;

    if (empty($m)) {
        echo json_encode(["status" => "error", "message" => "A mensagem não pode estar vazia."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO messages (user_id, message, is_admin) VALUES (?, ?, ?)");
    if(!$stmt) {
        throw new Exception("Erro na preparação (prepare): " . $conn->error);
    }

    $stmt->bind_param("isi", $u, $m, $is_admin);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao executar (execute): " . $stmt->error]);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Exceção: " . $e->getMessage()]);
} catch (Error $e) {
    echo json_encode(["status" => "error", "message" => "Erro Fatal: " . $e->getMessage()]);
}

$conn->close();
