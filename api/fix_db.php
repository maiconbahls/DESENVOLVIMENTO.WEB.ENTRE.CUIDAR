<?php
require 'config.php';

// Script para ajustar o banco de dados e adicionar a coluna de produto se não existir
$sql = "ALTER TABLE users ADD COLUMN IF NOT EXISTS produto_interesse VARCHAR(50) DEFAULT 'Não informado'";

if ($conn->query($sql)) {
    echo "Sucesso: Banco de dados atualizado. Agora você pode cadastrar o plano de interesse.";
} else {
    // Se o IF NOT EXISTS falhar por versão do MySQL antiga, tentamos sem ele e ignoramos o erro se já existir
    $sql_fallback = "ALTER TABLE users ADD COLUMN produto_interesse VARCHAR(50) DEFAULT 'Não informado'";
    if ($conn->query($sql_fallback)) {
        echo "Sucesso: Coluna adicionada.";
    } else {
        echo "Aviso: O banco de dados já parece estar atualizado ou houve um erro: " . $conn->error;
    }
}

$conn->close();
?>
