<?php
require_once 'config.php';

// Função para obter todos os produtos
function getAllProducts() {
    return supabaseRequest('/rest/v1/products?select=*');
}

// Função para obter um produto específico
function getProduct($productId) {
    return supabaseRequest('/rest/v1/products?id=eq.' . $productId);
}

// Função para criar um novo produto
function createProduct($productData) {
    return supabaseRequest('/rest/v1/products', 'POST', $productData);
}

// Função para atualizar um produto
function updateProduct($productId, $productData) {
    return supabaseRequest('/rest/v1/products?id=eq.' . $productId, 'PATCH', $productData);
}

// Função para deletar um produto
function deleteProduct($productId) {
    return supabaseRequest('/rest/v1/products?id=eq.' . $productId, 'DELETE');
}

// Tratamento das requisições
$method = $_SERVER['REQUEST_METHOD'];
$response = ['error' => 'Método não suportado'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $response = getProduct($_GET['id']);
        } else {
            $response = getAllProducts();
        }
        break;
    
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $response = createProduct($data);
        break;
    
    case 'PATCH':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            $response = updateProduct($data['id'], $data);
        }
        break;
    
    case 'DELETE':
        if (isset($_GET['id'])) {
            $response = deleteProduct($_GET['id']);
        }
        break;
}

header('Content-Type: application/json');
echo json_encode($response);
?> 