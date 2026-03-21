<?php
/**
 * validar_aluno.php
 * POST: nome, turma
 * Retorna: { sucesso, aluno_id }
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

require_once("./db.php");

$raw = file_get_contents('php://input');
$body = json_decode($raw, true);

$nome = isset($body['nome']) ? trim($body['nome']) : '';
$turma = isset($body['turma']) ? trim($body['turma']) : '';
$tema = isset($body['tema']) ? trim($body['tema']) : 'geral';

// Sanitiza turma (permite letras, números, espaços, hifens e sublinhados)
$turma = preg_replace('/[^a-zA-Z0-9\-_ ]/', '', $turma);

if (empty($nome) || empty($turma)) {
    echo json_encode(['erro' => 'Nome e Turma são obrigatórios.']);
    exit;
}

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );

    // 1. Valida se o aluno existe na turma informada
    $stmt = $pdo->prepare("SELECT id, nome FROM alunos WHERE LOWER(TRIM(nome)) = LOWER(TRIM(:nome)) AND LOWER(TRIM(turma)) = LOWER(TRIM(:turma)) LIMIT 1");
    $stmt->execute([':nome' => $nome, ':turma' => $turma]);
    $aluno = $stmt->fetch();

    if ($aluno) {
        $alunoId = $aluno['id'];

        // 2. Verifica se este ID de aluno já possui pontuação salva para este tema
        // IMPORTANTE: Verificamos pelo aluno_id, que é único e imutável
        $stmtCheck = $pdo->prepare("SELECT id FROM pontuacoes WHERE aluno_id = :aid AND tema = :tema LIMIT 1");
        $stmtCheck->execute([':aid' => $alunoId, ':tema' => $tema]);
        
        if ($stmtCheck->fetch()) {
            echo json_encode([
                'sucesso' => false, 
                'erro' => 'Você já realizou este desafio (' . $tema . ')! O sistema registrou sua participação e não permite refazer para garantir a integridade do diagnóstico.',
                'ja_fez' => true
            ]);
        } else {
            echo json_encode(['sucesso' => true, 'aluno_id' => $alunoId]);
        }
    } else {
        echo json_encode(['sucesso' => false, 'erro' => 'Dados não conferem. Verifique se seu nome e turma (' . $turma . ') estão corretos ou procure o professor.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no servidor: ' . $e->getMessage()]);
}