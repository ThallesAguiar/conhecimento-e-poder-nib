<?php
/**
 * getRanking.php
 * GET params:
 *   tipo  = diario | geral
 *   turma = (opcional) filtra por turma específica
 *
 * Retorna JSON: { rows: [...], turmas: [...lista de turmas distintas] }
 * Ordenação: acertos DESC → erros ASC → tempo ASC
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once("./db.php");

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'geral';
$turma = isset($_GET['turma']) ? trim($_GET['turma']) : '';
$tema = isset($_GET['tema']) ? trim($_GET['tema']) : 'geral';

// Sanitiza turma e tema (permite letras, números, espaços, hifens e sublinhados)
$turma = preg_replace('/[^a-zA-Z0-9\-_ ]/', '', $turma);
$tema = preg_replace('/[^a-zA-Z0-9\-_ ]/', '', $tema);

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro de conexão.']);
    exit;
}

// Monta condições WHERE
$where = [];
$params = [];

// Sempre filtra por tema
$where[] = 'tema = :tema';
$params[':tema'] = $tema;

if ($tipo === 'diario') {
    $where[] = 'DATE(criado_em) = CURDATE()';
}

if ($turma !== '') {
    $where[] = 'turma = :turma';
    $params[':turma'] = $turma;
}

$whereSQL = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

// Ranking principal
$sql = "
    SELECT
        nome,
        turma,
        acertos,
        erros,
        tempo_total_segundos,
        criado_em
    FROM pontuacoes
    $whereSQL
    ORDER BY
        acertos              DESC,
        erros                ASC,
        tempo_total_segundos ASC
    LIMIT 50
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

foreach ($rows as &$row) {
    $row['acertos'] = (int) $row['acertos'];
    $row['erros'] = (int) $row['erros'];
    $row['tempo_total_segundos'] = (int) $row['tempo_total_segundos'];
}
unset($row);

// Lista de turmas distintas para este tema específico
$turmaWhere = ["tema = :tema"];
if ($tipo === 'diario') $turmaWhere[] = "DATE(criado_em) = CURDATE()";
$turmaWhereSQL = "WHERE " . implode(" AND ", $turmaWhere);

$turmaStmt = $pdo->prepare("
    SELECT DISTINCT turma FROM pontuacoes $turmaWhereSQL
    ORDER BY turma ASC
");
$turmaStmt->execute([':tema' => $tema]);
$turmas = array_column($turmaStmt->fetchAll(), 'turma');

echo json_encode(
    ['rows' => $rows, 'turmas' => $turmas],
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
);
