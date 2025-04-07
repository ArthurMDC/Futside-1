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
    if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
        throw new Exception('Campos obrigatórios não preenchidos');
    }

    // Sanitizar e validar dados
    $name = sanitizeInput($data['name']);
    $email = sanitizeInput($data['email']);
    $password = $data['password'];

    // Validar email
    if (!validateEmail($email)) {
        throw new Exception('Email inválido');
    }

    // Validar senha
    if (!validatePassword($password)) {
        throw new Exception('Senha deve ter pelo menos 6 caracteres');
    }

    // Conectar ao banco de dados
    $conn = getConnection();

    // Verificar se email já existe
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        throw new Exception('Email já cadastrado');
    }

    // Hash da senha
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Inserir usuário
    $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $name, $email, $passwordHash);
    
    if (!$stmt->execute()) {
        throw new Exception('Erro ao cadastrar usuário');
    }

    // Retornar sucesso
    echo json_encode([
        'success' => true,
        'message' => 'Usuário cadastrado com sucesso'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 