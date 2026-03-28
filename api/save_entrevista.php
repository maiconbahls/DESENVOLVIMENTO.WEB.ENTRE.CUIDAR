<?php
require 'config.php';

$data = json_decode(file_get_contents("php://input"));
if(!isset($data->user_id)) {
    echo json_encode(["status" => "error", "message" => "Dados inválidos"]);
    exit;
}

$u = $data->user_id;
$emp = $data->empresa;
$res = $data->respondente;
$car = $data->cargo;
$dat = $data->data;
$por = $data->porte;
$reg = $data->regiao;

// Seção 2
$q1 = $data->q1;
$q2 = $data->q2;
$q3 = $data->q3;

// Seção 3
$q4 = $data->q4;
$q5 = $data->q5;
$q6 = $data->q6;
$q7 = $data->q7;

// Matriz (Gravidades)
$g1=$data->g1;$g2=$data->g2;$g3=$data->g3;$g4=$data->g4;$g5=$data->g5;$g6=$data->g6;$g7=$data->g7;$g8=$data->g8;$g9=$data->g9;$g10=$data->g10;$g11=$data->g11;

// Matriz (Frequências)
$f1=$data->f1;$f2=$data->f2;$f3=$data->f3;$f4=$data->f4;$f5=$data->f5;$f6=$data->f6;$f7=$data->f7;$f8=$data->f8;$f9=$data->f9;$f10=$data->f10;$f11=$data->f11;

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
