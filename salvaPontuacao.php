<?php
/**
 * salvaPontuacao.php
 * POST: nome, turma, acertos, erros, tempo_total_segundos
 * Retorna: { sucesso, id, posicao, posicao_turma }
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Use POST.']);
    exit;
}

require_once("./db.php");

// Configuração de Fuso Horário redundante para segurança
date_default_timezone_set('America/Sao_Paulo');

$raw = file_get_contents('php://input');
$body = json_decode($raw, true) ?: $_POST;

$nome = isset($body['nome']) ? trim($body['nome']) : '';
$turma = isset($body['turma']) ? trim($body['turma']) : 'sem_turma';
$tema = isset($body['tema']) ? trim($body['tema']) : 'geral';
$acertos = isset($body['acertos']) ? (int) $body['acertos'] : 0;
$erros = isset($body['erros']) ? (int) $body['erros'] : 0;
$tempo = isset($body['tempo_total_segundos']) ? (int) $body['tempo_total_segundos'] : 0;

// Validações
if (strlen($nome) < 2 || strlen($nome) > 100) {
    http_response_code(422);
    echo json_encode(['erro' => 'Nome inválido.']);
    exit;
}
if ($acertos < 0 || $acertos > 20 || $erros < 0 || $erros > 20) {
    http_response_code(422);
    echo json_encode(['erro' => 'Valores fora do intervalo.']);
    exit;
}
if ($tempo < 0 || $tempo > 3600) {
    http_response_code(422);
    echo json_encode(['erro' => 'Tempo inválido.']);
    exit;
}

// Sanitiza turma e tema
$turma = preg_replace('/[^a-zA-Z0-9\-_]/', '', $turma);
$turma = substr($turma ?: 'sem_turma', 0, 30);
$tema = preg_replace('/[^a-zA-Z0-9\-_]/', '', $tema);
$tema = substr($tema ?: 'geral', 0, 50);

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );

    // Garantia extra: Cria a coluna tema se ela não existir (auto-correção)
    try {
        $pdo->exec("ALTER TABLE pontuacoes ADD COLUMN tema VARCHAR(50) NOT NULL DEFAULT 'geral' AFTER turma");
        $pdo->exec("ALTER TABLE pontuacoes ADD INDEX idx_tema (tema)");
    } catch (Exception $e) { /* Coluna já existe, ignora */ }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro de conexão: ' . $e->getMessage()]);
    exit;
}

try {
    // Insert
    $stmt = $pdo->prepare("
        INSERT INTO pontuacoes (nome, turma, tema, acertos, erros, tempo_total_segundos)
        VALUES (:nome, :turma, :tema, :acertos, :erros, :tempo)
    ");
    $stmt->execute([':nome' => $nome, ':turma' => $turma, ':tema' => $tema, ':acertos' => $acertos, ':erros' => $erros, ':tempo' => $tempo]);
    $newId = (int) $pdo->lastInsertId();

    // Posição geral (apenas no tema atual)
    $stmtPos = $pdo->prepare("
        SELECT COUNT(*) + 1 AS posicao FROM pontuacoes
        WHERE tema = :tema
          AND (
              acertos > :a
           OR (acertos = :a AND erros < :e)
           OR (acertos = :a AND erros = :e AND tempo_total_segundos < :t)
          )
    ");
    $stmtPos->execute([':tema' => $tema, ':a' => $acertos, ':e' => $erros, ':t' => $tempo]);
    $posGeral = (int) $stmtPos->fetchColumn();

    // Posição dentro da turma (apenas no tema atual)
    $stmtTurma = $pdo->prepare("
        SELECT COUNT(*) + 1 AS posicao FROM pontuacoes
        WHERE tema = :tema
          AND turma = :turma
          AND (
              acertos > :a
           OR (acertos = :a AND erros < :e)
           OR (acertos = :a AND erros = :e AND tempo_total_segundos < :t)
          )
    ");
    $stmtTurma->execute([':tema' => $tema, ':turma' => $turma, ':a' => $acertos, ':e' => $erros, ':t' => $tempo]);
    $posTurma = (int) $stmtTurma->fetchColumn();

    echo json_encode([
        'sucesso' => true,
        'id' => $newId,
        'posicao' => $posGeral,
        'posicao_turma' => $posTurma,
        'turma' => $turma,
        'tema' => $tema,
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao salvar: ' . $e->getMessage()]);
}
