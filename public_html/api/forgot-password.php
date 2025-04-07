<?php
require_once 'config.php';

try {
    // Verifica se é uma requisição POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        returnError('Método não permitido. Use POST.', 405);
    }

    // Recebe e decodifica os dados JSON
    $json = file_get_contents('php://input');
    if (!$json) {
        returnError('Nenhum dado recebido. Envie um JSON com o campo email.');
    }

    $data = json_decode($json, true);
    if (!$data) {
        returnError('JSON inválido. Verifique o formato dos dados.');
    }

    // Valida campo obrigatório
    if (!isset($data['email']) || trim($data['email']) === '') {
        returnError('O campo email é obrigatório');
    }

    // Sanitiza e valida o email
    $email = sanitizeInput($data['email']);
    if (!isValidEmail($email)) {
        returnError('Email inválido');
    }

    // Conecta ao banco de dados
    $conn = getConnection();

    // Verifica se o email existe
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if (!$stmt->fetch()) {
        returnError('Email não encontrado em nossa base de dados');
    }

    // Gera token de recuperação
    $token = generateToken();
    $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Remove tokens antigos do email
    $stmt = $conn->prepare('DELETE FROM password_resets WHERE email = ?');
    $stmt->execute([$email]);

    // Insere novo token
    $stmt = $conn->prepare('INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)');
    if (!$stmt->execute([$email, $token, $expires_at])) {
        throw new Exception('Erro ao gerar token de recuperação');
    }

    // TODO: Implementar envio de email
    // Por enquanto, retornamos o token para teste
    echo json_encode([
        'success' => true,
        'message' => 'Se o email existir em nossa base, você receberá as instruções de recuperação',
        'debug_token' => $token // Remover em produção
    ]);

} catch (PDOException $e) {
    error_log('Erro no banco de dados: ' . $e->getMessage());
    returnError('Erro interno do servidor. Por favor, tente novamente.', 500);
} catch (Exception $e) {
    error_log('Erro: ' . $e->getMessage());
    returnError($e->getMessage());
}
?> 