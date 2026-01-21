<?php
// view_deck.php - Visualização e edição de um deck em grade

require_once 'config.php';

// Valida o ID do deck a partir da URL
$deck_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$deck_id) {
    header('Location: decks.php');
    exit();
}

try {
    // Busca o nome do deck
    $stmt = $pdo->prepare("SELECT name FROM decks WHERE id = ?");
    $stmt->execute([$deck_id]);
    $deck = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$deck) {
        die("Deck não encontrado.");
    }

    // Busca todas as memórias do deck
    $stmt = $pdo->prepare("SELECT card_key, identifier FROM memories WHERE deck_id = ?");
    $stmt->execute([$deck_id]);
    $memories_raw = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // 'card_key' => 'identifier'

    // Define a ordem dos naipes e valores para a grade
    $naipes = ['spades', 'hearts', 'clubs', 'diamonds'];
    $valores = ['ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'jack', 'queen', 'king'];

} catch (PDOException $e) {
    die("Erro ao carregar o deck: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editando "<?php echo htmlspecialchars($deck['name']); ?>" - DeckMemo</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container grid-container">
        <a href="decks.php" class="back-link">&larr; Voltar para Meus Decks</a>
        <h1><?php echo htmlspecialchars($deck['name']); ?></h1>
        <p class="subtitle">Clique em um identificador para editá-lo. A alteração é salva automaticamente.</p>

        <div class="deck-grid">
            <?php foreach ($naipes as $naipe): ?>
                <div class="grid-column">
                    <div class="suit-header">
                        <img src="assets/images/suits/<?php echo $naipe; ?>.png" alt="<?php echo $naipe; ?>" class="suit-icon">
                    </div>
                    <?php foreach ($valores as $valor): ?>
                        <?php
                            $card_key = "{$valor}_of_{$naipe}";
                            $identifier = $memories_raw[$card_key] ?? '';
                        ?>
                        <div class="card-cell">
                            <img src="assets/images/cards/<?php echo $card_key; ?>.png" alt="<?php echo str_replace('_', ' ', $card_key); ?>" class="grid-card-image">
                            <input type="text"
                                   class="identifier-input"
                                   value="<?php echo htmlspecialchars($identifier); ?>"
                                   placeholder="Identificador"
                                   data-deck-id="<?php echo $deck_id; ?>"
                                   data-card-key="<?php echo $card_key; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="save-status"></div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
