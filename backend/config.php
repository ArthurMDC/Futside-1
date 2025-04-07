<?php
// Configurações do banco de dados
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'u891970282');  // Seu usuário real
define('DB_PASSWORD', 'Futside2025@');  // Sua senha do banco
define('DB_NAME', 'u891970282_Futside');  // Nome real do banco

// Configurações de CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Configurações gerais
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão com o banco de dados
function getConnection() {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if ($conn->connect_error) {
        die(json_encode(['error' => 'Conexão falhou: ' . $conn->connect_error]));
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

// Função para sanitizar input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?> 