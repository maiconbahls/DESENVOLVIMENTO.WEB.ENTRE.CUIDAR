<?php
require 'config.php';

// Ative a visualização de erros para o Setup
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>🔧 Setup de Banco de Dados - EntreCuidar</h2>";

// 1. Array de Comandos SQL para estruturar o Banco
$sql_commands = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        role ENUM('admin', 'empresa') NOT NULL DEFAULT 'empresa',
        empresa_name VARCHAR(150),
        contato_name VARCHAR(100),
        email VARCHAR(150) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    "CREATE TABLE IF NOT EXISTS messages (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) NOT NULL,
        mensagem TEXT NOT NULL,
        status ENUM('lido', 'naolido') DEFAULT 'naolido',
        sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )"
];

// 2. Executar as criações de tabelas
foreach ($sql_commands as $key => $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green'>✔ Tabela " . ($key + 1) . " criada ou já existente com sucesso.</p>";
    } else {
        echo "<p style='color:red'>❌ Erro ao criar Tabela " . ($key + 1) . ": " . $conn->error . "</p>";
    }
}

// 3. Criar a Conta de Gestão da Ariane protegida com cryptografia se ainda não existir
$admin_email = 'ariane@entrecuidar.com.br';
$check_admin = $conn->query("SELECT id FROM users WHERE email = '$admin_email'");

if ($check_admin->num_rows == 0) {
    // A Senha da ariane é "gestao" (você pode alterar depois via painel se criarmos uma tela)
    $hash = password_hash("gestao", PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (role, empresa_name, contato_name, email, password_hash) VALUES (?, ?, ?, ?, ?)");
    $role = 'admin';
    $empresa = 'Diretoria EntreCuidar';
    $contato = 'Ariane';
    $stmt->bind_param("sssss", $role, $empresa, $contato, $admin_email, $hash);
    
    if ($stmt->execute()) {
        echo "<p style='color:green'>✔ Conta Admin (Ariane) criada com SUCESSO. (Email: ariane@entrecuidar.com.br | Senha: gestao)</p>";
    } else {
        echo "<p style='color:red'>❌ Erro ao criar conta Admin: " . $stmt->error . "</p>";
    }
    $stmt->close();
} else {
    echo "<p style='color:blue'>💡 A conta Admin da Ariane já estava criada no banco de dados.</p>";
}

echo "<hr><p>Setup finalizado com sucesso. Você já pode deletar este arquivo `setup.php`  por segurança se quiser.</p>";
$conn->close();
?>
