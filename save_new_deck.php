<?php
// save_new_deck.php - Salva um novo deck no banco de dados

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create_deck_form.php');
    exit();
}

$deckName = trim($_POST['deck_name'] ?? '');
$cards = $_POST['cards'] ?? [];
$identifiers = $_POST['identifiers'] ?? [];

// Validação
if (empty($deckName) || count($cards) !== 52 || count($identifiers) !== 52) {
    die("Erro: Dados inválidos ou incompletos. Todos os 52 campos são obrigatórios.");
}

try {
    // Inicia uma transação para garantir a integridade dos dados
    $pdo->beginTransaction();

    // 1. Insere o novo deck na tabela 'decks'
    $stmt = $pdo->prepare("INSERT INTO decks (name) VALUES (?)");
    $stmt->execute([$deckName]);
    $deckId = $pdo->lastInsertId();

    // 2. Prepara a inserção das memórias
    $stmt = $pdo->prepare("INSERT INTO memories (deck_id, card_key, identifier) VALUES (?, ?, ?)");

    // 3. Itera sobre as cartas e insere cada memória
    for ($i = 0; $i < count($cards); $i++) {
        $stmt->execute([$deckId, $cards[$i], $identifiers[$i]]);
    }

    // Confirma a transação
    $pdo->commit();

    // Redireciona para a página de visualização do novo deck
    header('Location: view_deck.php?id=' . $deckId);
    exit();

} catch (PDOException $e) {
    // Desfaz a transação em caso de erro
    $pdo->rollBack();
    // Verifica se o erro é de violação de unicidade (nome do deck duplicado)
    if ($e->getCode() == 23000) {
        die("Erro ao salvar: Já existe um deck com o nome '{$deckName}'. Por favor, escolha outro nome.");
    } else {
        die("Erro ao salvar o deck no banco de dados: " . $e->getMessage());
    }
}
?>
