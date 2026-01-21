<?php
session_start();

// --- Lógica para gerar o baralho ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deck_count'])) {
    $deck_count = (int)$_POST['deck_count'];
    if ($deck_count > 0 && $deck_count <= 5) {
        $naipes = ['spades', 'hearts', 'diamonds', 'clubs'];
        $valores = ['ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'jack', 'queen', 'king'];
        $deck = [];
        foreach ($naipes as $naipe) {
            foreach ($valores as $valor) {
                $deck[] = "{$valor}_of_{$naipe}";
            }
        }

        // Multiplica o baralho e embaralha
        $full_sequence = [];
        for ($i = 0; $i < $deck_count; $i++) {
            $full_sequence = array_merge($full_sequence, $deck);
        }
        shuffle($full_sequence);

        // Armazena na sessão
        $_SESSION['game'] = [
            'sequence' => $full_sequence,
            'total_cards' => count($full_sequence),
        ];
    } else {
        header('Location: play.php');
        exit();
    }
} else {
    // Se não veio de play.php, redireciona
    header('Location: play.php');
    exit();
}

$game_sequence = $_SESSION['game']['sequence'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fase de Memorização - DeckMemo</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Memorize a Sequência</h1>
        <p>Clique em "Próxima" para ver todas as cartas. Tente decorar a ordem.</p>

        <div id="memorize-area">
            <div class="card-display">
                <img id="card-image" src="" alt="Carta do Baralho">
            </div>
            <div class="card-progress">
                <span id="card-counter"></span>
            </div>
            <div class="navigation">
                <button type="button" id="next-card" class="button">Próxima</button>
                <a href="recall.php" id="start-recall" class="button submit-button" style="display: none;">Iniciar Teste!</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sequence = <?php echo json_encode($game_sequence); ?>;
            const totalCards = sequence.length;
            let currentIndex = 0;

            const cardImage = document.getElementById('card-image');
            const cardCounter = document.getElementById('card-counter');
            const nextButton = document.getElementById('next-card');
            const startRecallButton = document.getElementById('start-recall');

            function updateView() {
                const cardKey = sequence[currentIndex];
                cardImage.src = `assets/images/cards/${cardKey}.png`;
                cardImage.alt = cardKey.replace(/_/g, ' ');
                cardCounter.textContent = `Carta ${currentIndex + 1} de ${totalCards}`;

                // Se for a última carta, esconde o botão "Próxima" e mostra "Iniciar Teste"
                if (currentIndex === totalCards - 1) {
                    nextButton.style.display = 'none';
                    startRecallButton.style.display = 'block';
                }
            }

            nextButton.addEventListener('click', () => {
                if (currentIndex < totalCards - 1) {
                    currentIndex++;
                    updateView();
                }
            });

            // Inicia a visualização
            updateView();
        });
    </script>
</body>
</html>
