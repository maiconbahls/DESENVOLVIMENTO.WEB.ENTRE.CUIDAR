<?php
require 'config.php';

echo "<h2>Iniciando atualização do banco de dados...</h2>";

$columns = [
    "empresa_name VARCHAR(255) DEFAULT 'Não informado'",
    "contato_name VARCHAR(255) DEFAULT 'Não informado'",
    "produto_interesse VARCHAR(100) DEFAULT 'Essencial'"
];

foreach ($columns as $col) {
    $colName = explode(' ', $col)[0];
    $sql = "ALTER TABLE users ADD COLUMN $col";
    if ($conn->query($sql)) {
        echo "<p style='color: green;'>Sucesso: Coluna <strong>$colName</strong> adicionada.</p>";
    } else {
        echo "<p style='color: orange;'>Aviso: Coluna <strong>$colName</strong> já existe ou não pôde ser adicionada.</p>";
    }
}

echo "<h3>Atualização concluída!</h3>";
echo "<p>Agora tente realizar o cadastro novamente no site.</p>";

$conn->close();
?>
