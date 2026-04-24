<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preencha com os dados fornecidos pelo banco da Hostinger
$host = "localhost";
$dbname = "u902256670_entrecuidar";
$user = "u902256670_admin";
$pass = "LOSpirado@2020.,";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Erro de Conexão com o Banco de Dados: " . $conn->connect_error]));
}

$conn->set_charset("utf8mb4");

// Ajuste de Fuso Horário para Brasil/São Paulo
date_default_timezone_set('America/Sao_Paulo');
$conn->query("SET time_zone = '-03:00'");
