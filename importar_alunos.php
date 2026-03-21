<?php
/**
 * importar_alunos.php
 * Script utilitário para cadastrar alunos no sistema via lista (CSV/Texto).
 * Formato esperado: Nome Completo;Turma
 */

require_once("./db.php");

$feedback = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['lista_alunos'])) {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR,
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        $linhas = explode("\n", $_POST['lista_alunos']);
        $importados = 0;
        $erros = 0;

        $stmt = $pdo->prepare("INSERT IGNORE INTO alunos (nome, turma) VALUES (:nome, :turma)");

        foreach ($linhas as $linha) {
            $partes = explode(";", trim($linha));
            if (count($partes) >= 2) {
                $nome = trim($partes[0]);
                $turma = trim($partes[1]);
                
                if (!empty($nome) && !empty($turma)) {
                    $stmt->execute([':nome' => $nome, ':turma' => $turma]);
                    if ($stmt->rowCount() > 0) $importados++;
                }
            }
        }
        $feedback = "<div class='alert alert-success'>Sucesso! $importados novos alunos cadastrados.</div>";
    } catch (PDOException $e) {
        $feedback = "<div class='alert alert-danger'>Erro: " . $e->getMessage() . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Importar Alunos - Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #07090f; color: #dce8f8; font-family: sans-serif; padding: 2rem; }
        .card { background: #0d1117; border: 1px solid rgba(255,255,255,0.1); color: #dce8f8; }
        textarea { background: #1a2236 !important; color: #fff !important; border-color: #5c6bc0 !important; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card p-4">
            <h3>Importar Lista de Alunos</h3>
            <p class="text-muted">Cole abaixo a lista no formato: <code>Nome do Aluno;Turma</code> (um por linha)</p>
            
            <?php echo $feedback; ?>

            <form method="POST">
                <textarea name="lista_alunos" class="form-control mb-3" rows="15" placeholder="Exemplo:&#10;João Silva;INFO-1A&#10;Maria Souza;INFO-1B"></textarea>
                <button type="submit" class="btn btn-primary w-100">CADASTRAR ALUNOS</button>
            </form>
            <div class="mt-3">
                <a href="index.html" class="text-info text-decoration-none">← Voltar para o Quiz</a>
            </div>
        </div>
    </div>
</body>
</html>