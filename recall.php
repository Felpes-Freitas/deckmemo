<?php
session_start();

// --- Validação do Jogo ---
if (!isset($_SESSION['game'])) {
    header('Location: play.php');
    exit();
}

$game = &$_SESSION['game']; // Usar referência para facilitar a escrita

// --- Inicialização do estado do jogo ---
if (!isset($game['recalled_cards'])) {
    $game['recalled_cards'] = [];
    $game['current_position'] = 0;
    $game['game_over'] = false;
    $game['error_info'] = null;
}

// --- Lógica para processar a tentativa ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guess'])) {
    if (!$game['game_over']) {
        $guess = $_POST['guess'];
        $correct_card = $game['sequence'][$game['current_position']];

        if ($guess === $correct_card) {
            $game['recalled_cards'][] = $guess;
            $game['current_position']++;
            // Verifica se o jogo terminou com vitória
            if ($game['current_position'] === $game['total_cards']) {
                $game['game_over'] = true;
            }
        } else {
            $game['game_over'] = true;
            $game['error_info'] = [
                'guessed_card' => $guess,
                'correct_card' => $correct_card
            ];
        }
    }
    // Redireciona para o mesmo script via GET para evitar reenvio do formulário
    header('Location: recall.php');
    exit();
}

// --- Gera as 52 cartas para os botões de palpite ---
$naipes = ['spades', 'hearts', 'diamonds', 'clubs'];
$valores = ['ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'jack', 'queen', 'king'];
$all_possible_cards = [];
foreach ($naipes as $naipe) {
    foreach ($valores as $valor) {
        $all_possible_cards[] = "{$valor}_of_{$naipe}";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fase de Teste - DeckMemo</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container recall-container">
    <h1>Digite a Sequência</h1>

    <?php if ($game['game_over']): ?>
        <?php if ($game['error_info']): // Derrota ?>
            <div class="game-over-message loss">
                <h2>Fim de Jogo! Você errou.</h2>
                <p>Você acertou <?php echo count($game['recalled_cards']); ?> de <?php echo $game['total_cards']; ?> cartas.</p>
                <div class="error-details">
                    <div>
                        <strong>Sua Resposta:</strong>
                        <img src="assets/images/cards/<?php echo $game['error_info']['guessed_card']; ?>.png" alt="Errada">
                    </div>
                    <div>
                        <strong>Resposta Correta:</strong>
                        <img src="assets/images/cards/<?php echo $game['error_info']['correct_card']; ?>.png" alt="Certa">
                    </div>
                </div>
            </div>
        <?php else: // Vitória ?>
            <div class="game-over-message win">
                <h2>Parabéns!</h2>
                <p>Você memorizou a sequência completa de <?php echo $game['total_cards']; ?> cartas!</p>
            </div>
        <?php endif; ?>
        <a href="play.php" class="button">Jogar Novamente</a>

    <?php else: // Jogo em andamento ?>
        <p>Selecione a carta correta para a posição <strong><?php echo $game['current_position'] + 1; ?></strong>.</p>
        <div class="recall-progress">
            <span>Progresso: <?php echo $game['current_position']; ?> / <?php echo $game['total_cards']; ?></span>
        </div>

        <form method="POST" action="recall.php" id="recall-form">
            <input type="hidden" name="guess" id="guess-input">
            <div class="card-selection-grid">
                <?php foreach ($all_possible_cards as $card): ?>
                    <button type="submit" name="guess" value="<?php echo $card; ?>" class="card-guess-button">
                        <img src="assets/images/cards/<?php echo $card; ?>.png" alt="<?php echo str_replace('_', ' ', $card); ?>">
                    </button>
                <?php endforeach; ?>
            </div>
        </form>

        <div class="recalled-sequence">
            <h3>Sua sequência até agora:</h3>
            <div class="recalled-cards-list">
                <?php if (empty($game['recalled_cards'])): ?>
                    <p>Nenhuma carta informada ainda.</p>
                <?php else: ?>
                    <?php foreach ($game['recalled_cards'] as $recalled_card): ?>
                        <img src="assets/images/cards/<?php echo $recalled_card; ?>.png" alt="Carta recordada">
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

</div>
</body>
</html>
