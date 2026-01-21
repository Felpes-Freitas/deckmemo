<?php
// update_identifier.php - Endpoint para salvar alterações de um identificador

require_once 'config.php';

// Define o cabeçalho da resposta como JSON
header('Content-Type: application/json');

// Verifica se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido.']);
    exit();
}

// Obtém os dados da requisição
$data = json_decode(file_get_contents('php://input'), true);

$deck_id = $data['deck_id'] ?? null;
$card_key = $data['card_key'] ?? null;
$identifier = $data['identifier'] ?? '';

// Validação simples
if (!$deck_id || !$card_key) {
    echo json_encode(['status' => 'error', 'message' => 'Dados incompletos.']);
    exit();
}

try {
    // Prepara e executa o UPDATE no banco de dados
    $stmt = $pdo->prepare(
        "UPDATE memories SET identifier = :identifier WHERE deck_id = :deck_id AND card_key = :card_key"
    );

    $stmt->execute([
        ':identifier' => $identifier,
        ':deck_id' => $deck_id,
        ':card_key' => $card_key
    ]);

    // Verifica se a linha foi de fato atualizada
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Identificador salvo com sucesso.']);
    } else {
        // Isso pode acontecer se o identificador salvo for o mesmo que já estava no banco
        echo json_encode(['status' => 'no_change', 'message' => 'Nenhuma alteração foi necessária.']);
    }

} catch (PDOException $e) {
    // Captura erros do banco de dados
    echo json_encode(['status' => 'error', 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>
