<?php
// config.php - Configuração e Conexão com o Banco de Dados

$db_path = __DIR__ . '/data/deckmemo.sqlite';
$db_needs_setup = !file_exists($db_path);

try {
    // Conecta ao banco de dados SQLite. O arquivo será criado se não existir.
    $pdo = new PDO('sqlite:' . $db_path);

    // Define o modo de erro para exceções, facilitando a depuração.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($db_needs_setup) {
        // Se o banco de dados foi recém-criado, cria as tabelas.
        
        // Tabela de Decks
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS decks (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Tabela de Memórias (identificadores das cartas)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS memories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                deck_id INTEGER NOT NULL,
                card_key TEXT NOT NULL,
                identifier TEXT,
                FOREIGN KEY (deck_id) REFERENCES decks(id) ON DELETE CASCADE
            )
        ");
    }

} catch (PDOException $e) {
    // Em caso de erro na conexão ou configuração, exibe uma mensagem e encerra.
    die("Erro ao conectar ou configurar o banco de dados: " . $e->getMessage());
}

// A variável $pdo estará disponível em qualquer script que inclua este arquivo.
?>
