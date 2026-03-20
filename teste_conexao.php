<?php
require_once("./db.php");

header('Content-Type: application/json; charset=utf-8');

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    // Verifica se a tabela existe e quais colunas ela tem
    $stmt = $pdo->query("DESCRIBE pontuacoes");
    $colunas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'status' => 'sucesso',
        'mensagem' => 'Conexão estabelecida com sucesso!',
        'banco' => DB_NAME,
        'host' => DB_HOST,
        'colunas_encontradas' => $colunas
    ], JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'erro',
        'mensagem' => $e->getMessage(),
        'host' => DB_HOST,
        'banco' => DB_NAME
    ], JSON_PRETTY_PRINT);
}
