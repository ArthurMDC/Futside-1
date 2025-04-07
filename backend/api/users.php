<?php
require_once '../config.php';

// Receber o método HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $conn = getConnection();
        
        // Se tiver ID, busca usuário específico
        if (isset($_GET['id'])) {
            $id = sanitizeInput($_GET['id']);
            $stmt = $conn->prepare("SELECT id, name, email FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'Usuário não encontrado']);
                exit;
            }
            
            echo json_encode($result->fetch_assoc());
        } else {
            // Lista todos os usuários
            $result = $conn->query("SELECT id, name, email FROM users");
            $users = [];
            
            while($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            
            echo json_encode($users);
        }
        break;
        
    case 'POST':
        // Criar novo usuário
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Nome, email e senha são obrigatórios']);
            exit;
        }
        
        $name = sanitizeInput($data['name']);
        $email = sanitizeInput($data['email']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $conn = getConnection();
        
        // Verificar se email já existe
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Email já cadastrado']);
            exit;
        }
        
        // Inserir novo usuário
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        
        if ($stmt->execute()) {
            $userId = $conn->insert_id;
            echo json_encode([
                'success' => true,
                'user' => [
                    'id' => $userId,
                    'name' => $name,
                    'email' => $email
                ]
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao criar usuário']);
        }
        break;
        
    case 'PUT':
        // Atualizar usuário
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID do usuário é obrigatório']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $id = sanitizeInput($_GET['id']);
        
        $conn = getConnection();
        
        $updates = [];
        $types = "";
        $values = [];
        
        if (isset($data['name'])) {
            $updates[] = "name = ?";
            $types .= "s";
            $values[] = sanitizeInput($data['name']);
        }
        
        if (isset($data['email'])) {
            $updates[] = "email = ?";
            $types .= "s";
            $values[] = sanitizeInput($data['email']);
        }
        
        if (isset($data['password'])) {
            $updates[] = "password = ?";
            $types .= "s";
            $values[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if (empty($updates)) {
            http_response_code(400);
            echo json_encode(['error' => 'Nenhum campo para atualizar']);
            exit;
        }
        
        $values[] = $id;
        $types .= "i";
        
        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$values);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao atualizar usuário']);
        }
        break;
        
    case 'DELETE':
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID do usuário é obrigatório']);
            exit;
        }
        
        $id = sanitizeInput($_GET['id']);
        $conn = getConnection();
        
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao deletar usuário']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
        break;
}
?> 