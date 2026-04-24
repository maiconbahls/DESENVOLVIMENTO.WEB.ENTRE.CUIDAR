<?php
require 'config.php';

// Failsafe: Create table if not exists (in case they didn't run setup)
$conn->query("CREATE TABLE IF NOT EXISTS entrevistas_completas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    empresa_nome VARCHAR(255),
    respondente_nome VARCHAR(255),
    respondente_cargo VARCHAR(255),
    data_entrevista DATE,
    porte_empresa VARCHAR(50),
    regiao_atuacao VARCHAR(255),
    q1_beneficiarios VARCHAR(100),
    q2_custo_mensal VARCHAR(100),
    q3_crescimento VARCHAR(100),
    q4_protocolo VARCHAR(100),
    q5_equipe_dedicada VARCHAR(100),
    q6_ferramenta_acompanhamento TEXT,
    q7_auditoria VARCHAR(100),
    g1 INT DEFAULT 0, g2 INT DEFAULT 0, g3 INT DEFAULT 0, g4 INT DEFAULT 0, 
    g5 INT DEFAULT 0, g6 INT DEFAULT 0, g7 INT DEFAULT 0, g8 INT DEFAULT 0, 
    g9 INT DEFAULT 0, g10 INT DEFAULT 0, g11 INT DEFAULT 0,
    f1 VARCHAR(50), f2 VARCHAR(50), f3 VARCHAR(50), f4 VARCHAR(50), 
    f5 VARCHAR(50), f6 VARCHAR(50), f7 VARCHAR(50), f8 VARCHAR(50), 
    f9 VARCHAR(50), f10 VARCHAR(50), f11 VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$sql = "SELECT * FROM entrevistas_completas ORDER BY created_at DESC";
$res = $conn->query($sql);

$data = [];
if($res) {
    while($row = $res->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode(["status" => "success", "data" => $data]);
?>
