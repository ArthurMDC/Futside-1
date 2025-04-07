<?php
// Habilitar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configurações do banco de dados
$hosts = [
    'srv805.hstgr.io',
    'localhost',
    '127.0.0.1'
];

$db_config = [
    'name' => 'u891970282_Futside1',
    'user' => 'u891970282_Futside01',
    'pass' => 'Futside2025@'
];

// Array para armazenar resultados
$results = [
    'php_version' => PHP_VERSION,
    'mysql_client_version' => mysqli_get_client_version(),
    'current_user' => get_current_user(),
    'server_software' => $_SERVER['SERVER_SOFTWARE'],
    'connection_tests' => []
];

// Testar cada host
foreach ($hosts as $host) {
    try {
        $mysqli = new mysqli($host, $db_config['user'], $db_config['pass'], $db_config['name']);
        
        $results['connection_tests'][$host] = [
            'success' => !$mysqli->connect_error,
            'message' => $mysqli->connect_error ? $mysqli->connect_error : 'Conexão bem sucedida'
        ];
        
        $mysqli->close();
    } catch (Exception $e) {
        $results['connection_tests'][$host] = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

// Retornar resultados em JSON
header('Content-Type: application/json');
echo json_encode($results, JSON_PRETTY_PRINT);
?> 