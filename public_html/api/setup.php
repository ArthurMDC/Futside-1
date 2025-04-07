<?php
// Habilitar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'u881970282_Futside1');  // Corrigido com 88
define('DB_USER', 'u881970282_Futside01');  // Corrigido com 88 e 01
define('DB_PASS', 'Futside2025');

try {
    // Conectar ao MySQL
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    
    if ($conn->connect_error) {
        throw new Exception("Erro na conexão: " . $conn->connect_error . " (Erro #" . $conn->connect_errno . ")");
    }
    
    // Criar o banco de dados se não existir
    if (!$conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
        throw new Exception("Erro ao criar banco de dados: " . $conn->error);
    }
    
    // Seleciona o banco de dados
    if (!$conn->select_db(DB_NAME)) {
        throw new Exception("Erro ao selecionar banco de dados: " . $conn->error);
    }
    
    // Criar tabela de usuários
    $result = $conn->query("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    if (!$result) {
        throw new Exception("Erro ao criar tabela users: " . $conn->error);
    }

    // Criar tabela de sessões
    $result = $conn->query("
        CREATE TABLE IF NOT EXISTS sessions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token VARCHAR(255) NOT NULL UNIQUE,
            expires_at TIMESTAMP NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    
    if (!$result) {
        throw new Exception("Erro ao criar tabela sessions: " . $conn->error);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Banco de dados e tabelas criados com sucesso'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao configurar banco de dados: ' . $e->getMessage()
    ]);
}
?> 