<?php
session_start();
// Limpa qualquer jogo anterior da sessão
session_unset();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogar - DeckMemo</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">&larr; Voltar para o Início</a>
        <h1>Modo de Jogo: Memorização</h1>
        <p>Escolha quantos baralhos você quer memorizar. As cartas serão embaralhadas e apresentadas em sequência para você decorar. Em seguida, você deverá digitar a sequência completa na ordem correta.</p>

        <form action="memorize.php" method="POST">
            <div class="form-group">
                <label for="deck-count">Número de Baralhos (1 a 5):</label>
                <input type="number" id="deck-count" name="deck_count" value="1" min="1" max="5" required>
            </div>
            <button type="submit" class="button submit-button">Iniciar Memorização</button>
        </form>
    </div>
</body>
</html>
