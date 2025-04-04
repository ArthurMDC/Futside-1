<?php
require_once 'config.php';

// Função para criar um novo usuário
function createUser($userData) {
    return supabaseRequest('/auth/v1/signup', 'POST', $userData);
}

// Função para fazer login
function loginUser($credentials) {
    return supabaseRequest('/auth/v1/token?grant_type=password', 'POST', $credentials);
}

// Função para obter dados do usuário
function getUserData($userId) {
    return supabaseRequest('/rest/v1/users?id=eq.' . $userId);
}

// Função para atualizar dados do usuário
function updateUser($userId, $userData) {
    return supabaseRequest('/rest/v1/users?id=eq.' . $userId, 'PATCH', $userData);
}

// Tratamento das requisições
$method = $_SERVER['REQUEST_METHOD'];
$response = ['error' => 'Método não suportado'];

switch ($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'signup':
                    $response = createUser($data);
                    break;
                case 'login':
                    $response = loginUser($data);
                    break;
            }
        }
        break;
    
    case 'GET':
        if (isset($_GET['userId'])) {
            $response = getUserData($_GET['userId']);
        }
        break;
    
    case 'PATCH':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['userId'])) {
            $response = updateUser($data['userId'], $data);
        }
        break;
}

header('Content-Type: application/json');
echo json_encode($response);
?> 