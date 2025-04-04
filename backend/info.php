<?php
require_once 'config.php';

header('Content-Type: application/json');

// Recebe os dados do POST
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Email e senha são obrigatórios']);
    exit;
}

// Tenta fazer login usando a API do Supabase
$loginData = [
    'email' => $data['email'],
    'password' => $data['password']
];

$response = supabaseRequest('/auth/v1/token?grant_type=password', 'POST', $loginData);

if ($response['status'] === 200) {
    echo json_encode([
        'success' => true,
        'data' => $response['data']
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => 'Email ou senha inválidos'
    ]);
}
?> 