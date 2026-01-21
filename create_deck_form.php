<?php
// create_deck_form.php - Formulário para criar um novo deck

// Define o baralho padrão
$naipes = ['spades', 'hearts', 'diamonds', 'clubs'];
$valores = ['ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'jack', 'queen', 'king'];
$cartas = [];
foreach ($naipes as $naipe) {
    foreach ($valores as $valor) {
        $cartas[] = "{$valor}_of_{$naipe}";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Novo Deck - DeckMemo</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <a href="decks.php" class="back-link">&larr; Cancelar</a>
        <h1>Criar Novo Deck</h1>
        <form id="deck-form" action="save_new_deck.php" method="POST">
            <div class="form-group">
                <label for="deck-name">Nome do Deck:</label>
                <input type="text" id="deck-name" name="deck_name" required placeholder="Ex: Meu Palácio da Memória">
            </div>

            <div id="card-creator">
                <div class="card-display">
                    <img id="card-image" src="" alt="Carta do Baralho">
                </div>
                <div class="card-progress">
                    <span id="card-counter"></span>
                </div>
                <div class="form-group">
                    <label for="card-identifier">Identificador para esta carta:</label>
                    <input type="text" id="card-identifier" name="identifiers[]" required>
                </div>
                <div class="navigation">
                    <button type="button" id="prev-card" class="button">Anterior</button>
                    <button type="button" id="next-card" class="button">Próxima</button>
                </div>
            </div>

            <button type="submit" class="button submit-button">Salvar Deck Completo</button>
        </form>
    </div>

    <script>
        // Passa as cartas do PHP para o JavaScript
        const cardList = <?php echo json_encode($cartas); ?>;
    </script>
    <script src="assets/js/script.js"></script>
</body>
</html>
