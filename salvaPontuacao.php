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
$alunoId = isset($body['aluno_id']) ? (int) $body['aluno_id'] : null;
$respostas = isset($body['respostas']) ? $body['respostas'] : []; // Array de { questao, resposta, correta }

// Validações
if (strlen($nome) < 2 || strlen($nome) > 100) {
    http_response_code(422);
    echo json_encode(['erro' => 'Nome inválido.']);
    exit;
}
if ($acertos < 0 || $acertos > 50 || $erros < 0 || $erros > 50) {
    http_response_code(422);
    echo json_encode(['erro' => 'Valores fora do intervalo.']);
    exit;
}
if ($tempo < 0 || $tempo > 7200) {
    http_response_code(422);
    echo json_encode(['erro' => 'Tempo inválido.']);
    exit;
}

// Sanitiza turma e tema
$turma = preg_replace('/[^a-zA-Z0-9\-_ ]/', '', $turma);
$turma = substr($turma ?: 'sem_turma', 0, 30);
$tema = preg_replace('/[^a-zA-Z0-9\-_ ]/', '', $tema);
$tema = substr($tema ?: 'geral', 0, 50);

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro de conexão: ' . $e->getMessage()]);
    exit;
}

try {
    $pdo->beginTransaction();

    // Insert Pontuação
    $stmt = $pdo->prepare("
        INSERT INTO pontuacoes (aluno_id, nome, turma, tema, acertos, erros, tempo_total_segundos)
        VALUES (:aluno_id, :nome, :turma, :tema, :acertos, :erros, :tempo)
    ");
    $stmt->execute([
        ':aluno_id' => $alunoId,
        ':nome' => $nome,
        ':turma' => $turma,
        ':tema' => $tema,
        ':acertos' => $acertos,
        ':erros' => $erros,
        ':tempo' => $tempo
    ]);
    $newId = (int) $pdo->lastInsertId();

    // Salvar respostas detalhadas se existirem
    if (!empty($respostas) && is_array($respostas)) {
        $stmtResp = $pdo->prepare("
            INSERT INTO respostas_detalhadas (pontuacao_id, questao_nome, resposta_aluno, correta)
            VALUES (:pid, :qnome, :resp, :corr)
        ");
        foreach ($respostas as $r) {
            $stmtResp->execute([
                ':pid' => $newId,
                ':qnome' => substr($r['questao'] ?? 'N/A', 0, 100),
                ':resp' => substr($r['resposta'] ?? 'N/A', 0, 50),
                ':corr' => !empty($r['correta']) ? 1 : 0
            ]);
        }
    }

    $pdo->commit();

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
