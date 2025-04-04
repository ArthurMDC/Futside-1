<?php
require_once 'config.php';

header('Content-Type: application/json');

// Ativa exibição de erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Log da requisição recebida
$rawData = file_get_contents('php://input');
error_log("Dados recebidos: " . $rawData);

// Recebe os dados do POST
$data = json_decode($rawData, true);

if (!$data || !isset($data['email']) || !isset($data['password']) || !isset($data['name'])) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Email, senha e nome são obrigatórios',
        'received_data' => $data
    ]);
    exit;
}

// Dados para registro
$userData = [
    'email' => $data['email'],
    'password' => $data['password'],
    'data' => [
        'name' => $data['name']
    ]
];

// Log dos dados que serão enviados
error_log("Dados para envio ao Supabase: " . json_encode($userData));

// Tenta registrar usando a API do Supabase
$response = supabaseRequest('/auth/v1/signup', 'POST', $userData);

// Log da resposta do Supabase
error_log("Resposta do Supabase: " . json_encode($response));

if ($response['status'] === 200) {
    echo json_encode([
        'success' => true,
        'message' => 'Usuário registrado com sucesso',
        'data' => $response['data']
    ]);
} else {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao registrar usuário',
        'details' => $response['data'],
        'status' => $response['status']
    ]);
}
?>