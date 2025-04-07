<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Configurações do banco de dados
$host = 'localhost';
$db   = 'u891970282_Futside';
$user = 'u891970282';
$pass = ''; // Coloque sua senha aqui
$charset = 'utf8mb4';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=$charset",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Recebe os dados do POST
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
        throw new Exception('Nome, email e senha são obrigatórios');
    }

    $name = trim($data['name']);
    $email = trim($data['email']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    // Verifica se o email já existe
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        throw new Exception('Email já cadastrado');
    }

    // Insere o novo usuário
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
    $stmt->execute([$name, $email, $password]);

    $userId = $pdo->lastInsertId();

    // Retorna os dados do usuário criado
    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $userId,
            'name' => $name,
            'email' => $email
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