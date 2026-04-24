<?php
require 'config.php';
// Failsafe: Create table if not exists
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

$data = json_decode(file_get_contents("php://input"));
if(!isset($data->user_id)) {
    echo json_encode(["status" => "error", "message" => "Dados inválidos"]);
    exit;
}

$u = $data->user_id ?? 0;
$emp = $data->empresa ?? '';
$res = $data->respondente ?? '';
$car = $data->cargo ?? '';
$dat = $data->data ?? date('Y-m-d');
$por = $data->porte ?? '';
$reg = $data->regiao ?? '';

// Seção 2
$q1 = $data->q1 ?? '';
$q2 = $data->q2 ?? '';
$q3 = $data->q3 ?? '';

// Seção 3
$q4 = $data->q4 ?? '';
$q5 = $data->q5 ?? '';
$q6 = $data->q6 ?? '';
$q7 = $data->q7 ?? '';

// Matriz (Gravidades)
for($i=1;$i<=11;$i++) {
    $gField = "g$i";
    ${"g$i"} = $data->$gField ?? 0;
}

// Matriz (Frequências)
for($i=1;$i<=11;$i++) {
    $fField = "f$i";
    ${"f$i"} = $data->$fField ?? '';
}

$sql = "INSERT INTO entrevistas_completas (
    user_id, empresa_nome, respondente_nome, respondente_cargo, data_entrevista, porte_empresa, regiao_atuacao,
    q1_beneficiarios, q2_custo_mensal, q3_crescimento,
    q4_protocolo, q5_equipe_dedicada, q6_ferramenta_acompanhamento, q7_auditoria,
    g1, g2, g3, g4, g5, g6, g7, g8, g9, g10, g11,
    f1, f2, f3, f4, f5, f6, f7, f8, f9, f10, f11
) VALUES (
    ?, ?, ?, ?, ?, ?, ?,
    ?, ?, ?,
    ?, ?, ?, ?,
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isssssssssssssiiiiiiiiiiisssssssssss", 
    $u, $emp, $res, $car, $dat, $por, $reg,
    $q1, $q2, $q3,
    $q4, $q5, $q6, $q7,
    $g1, $g2, $g3, $g4, $g5, $g6, $g7, $g8, $g9, $g10, $g11,
    $f1, $f2, $f3, $f4, $f5, $f6, $f7, $f8, $f9, $f10, $f11
);

if($stmt->execute()) {
    echo json_encode(["status" => "success", "id" => $stmt->insert_id]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>
