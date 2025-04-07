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

    if (!isset($data['email']) || !isset($data['password'])) {
        throw new Exception('Email e senha são obrigatórios');
    }

    $email = $data['email'];
    $password = $data['password'];

    // Prepara e executa a consulta
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        throw new Exception('Usuário não encontrado');
    }

    // Verifica a senha
    if (!password_verify($password, $user['password'])) {
        throw new Exception('Senha incorreta');
    }

    // Remove a senha do retorno
    unset($user['password']);

    // Retorna os dados do usuário
    echo json_encode([
        'success' => true,
        'user' => $user
    ]);

} catch (Exception $e) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 