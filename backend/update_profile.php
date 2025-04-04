<?php
require_once 'config.php';

header('Content-Type: application/json');

// Verifica se tem token de autenticação
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Token de autenticação não fornecido']);
    exit;
}

// Recebe os dados do POST
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Nome é obrigatório']);
    exit;
}

// Atualiza o perfil usando a API do Supabase
$response = supabaseRequest(
    '/auth/v1/user',
    'PUT',
    ['data' => ['name' => $data['name']]],
    $headers['Authorization']
);

if ($response['status'] === 200) {
    echo json_encode([
        'success' => true,
        'message' => 'Perfil atualizado com sucesso',
        'data' => $response['data']
    ]);
} else {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao atualizar perfil',
        'details' => $response['data']
    ]);
}
?> 