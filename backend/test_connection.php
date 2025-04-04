<?php
require_once 'config.php';

echo "Testando conexão com o Supabase...\n\n";

// Teste 1: Conexão PDO com PostgreSQL
echo "1. Testando conexão PostgreSQL:\n";
try {
    $pdo = getDbConnection();
    echo "✅ Conexão PostgreSQL estabelecida com sucesso!\n";
    
    // Tenta fazer uma consulta simples
    $stmt = $pdo->query("SELECT current_timestamp;");
    $row = $stmt->fetch(PDO::FETCH_NUM);
    echo "   Hora do servidor: " . $row[0] . "\n";
} catch (PDOException $e) {
    echo "❌ Erro ao conectar ao PostgreSQL: " . $e->getMessage() . "\n";
}

echo "\n";

// Teste 2: API do Supabase
echo "2. Testando API do Supabase:\n";
try {
    $response = supabaseRequest('/rest/v1/rpc/healthcheck', 'GET');
    
    if ($response['status'] === 200) {
        echo "✅ API do Supabase está funcionando!\n";
        echo "   Status: " . $response['status'] . "\n";
    } else {
        echo "❌ Erro na API do Supabase\n";
        echo "   Status: " . $response['status'] . "\n";
        echo "   Resposta: " . print_r($response['data'], true) . "\n";
    }
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}

echo "\n";
echo "Teste de conexão concluído!\n";
?> 