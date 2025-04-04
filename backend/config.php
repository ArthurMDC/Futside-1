<?php
// Configurações do Supabase
define('SUPABASE_URL', 'https://oqaygaoschsnw1ebv1sz.supabase.co');
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im9xYXlnYW9zY2hzbncxZWJ2MXN6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MDk0MjU1NzAsImV4cCI6MjAyNTAwMTU3MH0.Wd_dkF5EFYhgTFTRGYFEI-0aCL-2XGEQLQAqTsVGwXE');

// Configurações de CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, apikey');

// Ativa exibição de erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Função para fazer requisições ao Supabase
function supabaseRequest($endpoint, $method = 'GET', $data = null, $authToken = null) {
    $ch = curl_init();
    
    $url = SUPABASE_URL . $endpoint;
    error_log("Fazendo requisição para: " . $url);
    
    $headers = [
        'apikey: ' . SUPABASE_KEY,
        'Content-Type: application/json'
    ];

    if ($authToken) {
        $headers[] = 'Authorization: ' . $authToken;
    } else {
        $headers[] = 'Authorization: Bearer ' . SUPABASE_KEY;
    }

    error_log("Headers: " . json_encode($headers));

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        $jsonData = json_encode($data);
        error_log("Dados enviados: " . $jsonData);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        error_log("Curl error: " . curl_error($ch));
    }
    
    error_log("Resposta HTTP: " . $httpCode);
    error_log("Resposta: " . $response);
    
    curl_close($ch);

    return [
        'status' => $httpCode,
        'data' => json_decode($response, true)
    ];
}

// Função para verificar se o token é válido
function verifyToken($token) {
    $response = supabaseRequest('/auth/v1/user', 'GET', null, $token);
    return $response['status'] === 200;
}
?>