<?php
require 'config.php';

$sql = "CREATE TABLE IF NOT EXISTS propostas (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) NOT NULL,
    q1 VARCHAR(255),
    q2 VARCHAR(255),
    q3 VARCHAR(255),
    q4 TEXT,
    q5 VARCHAR(255),
    status VARCHAR(50) DEFAULT 'Pendente',
    orcamento_valor DECIMAL(10,2) NULL,
    orcamento_produto VARCHAR(100) NULL,
    orcamento_detalhes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    respondido_em TIMESTAMP NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabela de Propostas e Orçamentos criada com sucesso.";
} else {
    echo "Erro ao criar tabela: " . $conn->error;
}

// Update the admin user email to adm@entrecuidar.com
$updateAdmin = "UPDATE users SET email = 'adm@entrecuidar.com' WHERE role = 'admin'";
$conn->query($updateAdmin);

$conn->close();
?>
