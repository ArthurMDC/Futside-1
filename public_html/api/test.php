<?php
// Habilita a exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o arquivo de configuração
require_once 'config.php';

// Array para debug
$debug = [
    'host' => DB_HOST,
    'database' => DB_NAME,
    'user' => DB_USER,
    'password_provided' => !empty(DB_PASS) ? 'YES' : 'NO',
    'php_version' => PHP_VERSION,
    'mysql_client_info' => mysqli_get_client_info(),
    'server_software' => $_SERVER['SERVER_SOFTWARE']
];

try {
    // Tenta conectar diretamente primeiro
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Verifica se houve erro na conexão
    if ($mysqli->connect_error) {
        throw new Exception($mysqli->connect_error);
    }

    // Tenta executar uma query simples
    $result = $mysqli->query('SELECT 1');
    if (!$result) {
        throw new Exception('Query Error: ' . $mysqli->error);
    }

    // Adiciona informações do servidor MySQL
    $debug['mysql_server_info'] = $mysqli->server_info;
    $debug['mysql_host_info'] = $mysqli->host_info;
    $debug['mysql_protocol_version'] = $mysqli->protocol_version;

    // Se chegou aqui, a conexão foi bem sucedida
    echo json_encode([
        'success' => true,
        'message' => 'Conexão estabelecida com sucesso!',
        'debug' => $debug
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    error_log('Erro no teste de conexão: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'debug' => $debug
    ], JSON_PRETTY_PRINT);
}
?> 