<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "u891970282";  // Substitua pelo seu usuário do banco
$password = "futside2023@";    // Substitua pela sua senha
$dbname = "futside_db";

// Criar conexão
$conn = new mysqli($servername, $username, $password);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Criar banco de dados
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Banco de dados criado com sucesso\n";
} else {
    echo "Erro ao criar banco de dados: " . $conn->error . "\n";
}

// Selecionar o banco de dados
$conn->select_db($dbname);

// Criar tabela de usuários
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabela users criada com sucesso\n";
} else {
    echo "Erro ao criar tabela: " . $conn->error . "\n";
}

$conn->close();
?> 