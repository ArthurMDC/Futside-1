<?php
require_once '../config.php';

// Receber o método HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'POST':
        // Receber dados do corpo da requisição
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['email']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Email e senha são obrigatórios']);
            exit;
        }
        
        $email = sanitizeInput($data['email']);
        $password = $data['password'];
        
        // Conectar ao banco
        $conn = getConnection();
        
        // Verificar se o usuário existe
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            http_response_code(401);
            echo json_encode(['error' => 'Usuário não encontrado']);
            exit;
        }
        
        $user = $result->fetch_assoc();
        
        // Verificar senha
        if (!password_verify($password, $user['password'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Senha incorreta']);
            exit;
        }
        
        // Remover senha do retorno
        unset($user['password']);
        
        echo json_encode([
            'success' => true,
            'user' => $user
        ]);
        
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
        break;
}
?> 