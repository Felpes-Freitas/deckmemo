<?php
// decks.php - Página para listar os decks existentes
require_once 'config.php';

try {
    // Busca todos os decks no banco de dados, ordenados pelo nome
    $stmt = $pdo->query("SELECT id, name FROM decks ORDER BY name ASC");
    $decks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar os decks: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Decks - DeckMemo</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">&larr; Voltar para o Início</a>
        <h1>Meus Decks</h1>

        <div class="deck-list">
            <?php if (empty($decks)): ?>
                <p>Nenhum deck criado ainda. Crie um novo!</p>
            <?php else: ?>
                <?php foreach ($decks as $deck): ?>
                    <a href="view_deck.php?id=<?php echo $deck['id']; ?>" class="deck-item">
                        <?php echo htmlspecialchars($deck['name']); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <a href="create_deck_form.php" class="add-deck-button" title="Criar Novo Deck">+</a>
    </div>
</body>
</html>