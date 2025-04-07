<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // Verificar se é uma requisição POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método não permitido');
    }

    // Obter dados do corpo da requisição
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        throw new Exception('Dados inválidos');
    }

    // Validar campos obrigatórios
    if (empty($data['email']) || empty($data['password'])) {
        throw new Exception('Email e senha são obrigatórios');
    }

    // Sanitizar e validar email
    $email = sanitizeInput($data['email']);
    if (!validateEmail($email)) {
        throw new Exception('Email inválido');
    }

    $password = $data['password'];

    // Conectar ao banco de dados
    $conn = getConnection();

    // Buscar usuário pelo email
    $stmt = $conn->prepare('SELECT id, name, email, password FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Usuário não encontrado');
    }

    $user = $result->fetch_assoc();

    // Verificar senha
    if (!password_verify($password, $user['password'])) {
        throw new Exception('Senha incorreta');
    }

    // Gerar token de acesso
    $token = generateToken();
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

    // Inserir token na tabela de sessões
    $stmt = $conn->prepare('INSERT INTO sessions (user_id, token, expires_at) VALUES (?, ?, ?)');
    $stmt->bind_param('iss', $user['id'], $token, $expiresAt);
    
    if (!$stmt->execute()) {
        throw new Exception('Erro ao criar sessão');
    }

    // Retornar sucesso com dados do usuário e token
    echo json_encode([
        'success' => true,
        'message' => 'Login realizado com sucesso',
        'data' => [
            'user_id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'token' => $token
        ]
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 