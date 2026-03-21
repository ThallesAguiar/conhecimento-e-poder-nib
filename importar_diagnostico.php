<?php
/**
 * importar_diagnostico.php
 * Importador Unificado: Suporta Upload de Arquivo CSV direto do Google Forms.
 */

require_once("./db.php");

$feedback = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['turma_destino'])) {
    $turma_destino = trim($_POST['turma_destino']);
    $linhas_processadas = 0;
    $conteudo_csv = "";

    // Verifica se foi enviado um arquivo ou se o texto foi colado
    $handle = null;
    if (isset($_FILES['arquivo_csv']) && $_FILES['arquivo_csv']['error'] === UPLOAD_ERR_OK) {
        $handle = fopen($_FILES['arquivo_csv']['tmp_name'], "r");
    } elseif (!empty($_POST['csv_diagnostico'])) {
        // Fallback para o caso de ainda querer colar o texto
        $temp = tmpfile();
        fwrite($temp, $_POST['csv_diagnostico']);
        fseek($temp, 0);
        $handle = $temp;
    } else {
        $feedback = "<div class='alert alert-warning'>Por favor, selecione um arquivo CSV ou cole o conteúdo.</div>";
    }

    if ($handle) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // Pula o cabeçalho
            fgetcsv($handle, 0, ",");

            while (($campos = fgetcsv($handle, 0, ",")) !== FALSE) {
                // Mapeamento: [1] Nome, [2] Idade, [3] Curso Facu, [4] Freq, [5] Uso, [6] Nível, [7] Sabe, [10] Dific, [14] Obj
                if (count($campos) < 7) continue;

                $nome = trim($campos[1]);
                $idade = (int) $campos[2];
                $curso_facu = trim($campos[3]); 
                $freq = trim($campos[4]);
                $uso = trim($campos[5]);
                $nivel = trim($campos[6]);
                $sabe = trim($campos[7]);
                $dific = trim($campos[10] ?? '');
                $obj = trim($campos[14] ?? '');

                if (empty($nome)) continue;

                $pdo->beginTransaction();

                // 1. Cadastra/Atualiza Aluno
                $stmtA = $pdo->prepare("
                    INSERT INTO alunos (nome, turma) 
                    VALUES (:nome, :turma) 
                    ON DUPLICATE KEY UPDATE turma = :turma_upd
                ");
                $stmtA->execute([':nome' => $nome, ':turma' => $turma_destino, ':turma_upd' => $turma_destino]);
                
                $stmtGetId = $pdo->prepare("SELECT id FROM alunos WHERE nome = :nome AND turma = :turma LIMIT 1");
                $stmtGetId->execute([':nome' => $nome, ':turma' => $turma_destino]);
                $alunoId = $stmtGetId->fetchColumn();

                // 2. Salva Perfil
                $stmtP = $pdo->prepare("
                    REPLACE INTO aluno_perfil (aluno_id, idade, curso_faculdade, frequencia, uso_principal, nivel_declarado, habilidades, dificuldades, objetivo)
                    VALUES (:aid, :idade, :cf, :freq, :uso, :nivel, :sabe, :dific, :obj)
                ");
                $stmtP->execute([
                    ':aid'   => $alunoId,
                    ':idade' => $idade,
                    ':cf'    => $curso_facu,
                    ':freq'  => $freq,
                    ':uso'   => $uso,
                    ':nivel' => $nivel,
                    ':sabe'  => $sabe,
                    ':dific' => $dific,
                    ':obj'   => $obj
                ]);

                $pdo->commit();
                $linhas_processadas++;
            }
            fclose($handle);

            $feedback = "<div class='alert alert-success'>🚀 <strong>Sucesso!</strong><br>$linhas_processadas alunos processados na turma <strong>$turma_destino</strong>.</div>";

        } catch (Exception $e) {
            if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
            $feedback = "<div class='alert alert-danger'>Erro: " . $e->getMessage() . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Importador Mestre - Quiz NIB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #07090f; color: #dce8f8; font-family: sans-serif; padding: 2rem; }
        .card { background: #0d1117; border: 1px solid rgba(255,255,255,0.1); color: #dce8f8; }
        textarea, input { background: #1a2236 !important; color: #fff !important; border-color: #5c6bc0 !important; }
        .instrucoes { background: rgba(92, 107, 192, 0.1); border-left: 4px solid #5c6bc0; padding: 1rem; margin-bottom: 1.5rem; }
        .upload-area { border: 2px dashed #5c6bc0; padding: 2rem; border-radius: 10px; text-align: center; margin-bottom: 1.5rem; transition: background 0.3s; }
        .upload-area:hover { background: rgba(92, 107, 192, 0.05); }
    </style>
</head>
<body>
    <div class="container" style="max-width: 700px;">
        <div class="card p-4 shadow">
            <h3 class="mb-3">📥 Importador Mestre</h3>
            
            <?php echo $feedback; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="form-label fw-bold">1. Qual a Turma deste grupo?</label>
                    <input type="text" name="turma_destino" class="form-control form-control-lg" placeholder="Ex: T01, NIB-2026..." required>
                    <div class="form-text text-muted">Este é o código que você usará no link: <code>?turma=...</code></div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">2. Selecione o arquivo do Forms (.csv)</label>
                    <div class="upload-area">
                        <input type="file" name="arquivo_csv" class="form-control mb-2" accept=".csv">
                        <div class="small text-muted">Arraste o arquivo aqui ou clique para selecionar</div>
                    </div>
                </div>

                <div class="accordion mb-4" id="accordionExample">
                    <div class="accordion-item" style="background: transparent; border: 1px solid var(--border);">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed small py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" style="background: transparent; color: #fff;">
                                Ou cole o conteúdo manualmente (opcional)
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <textarea name="csv_diagnostico" class="form-control" rows="5" placeholder="Se preferir, cole o conteúdo do CSV aqui..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">🚀 IMPORTAR TUDO AGORA</button>
            </form>
            
            <div class="mt-4 d-flex justify-content-between">
                <a href="index.html" class="text-info text-decoration-none small">← Voltar para o Quiz</a>
                <a href="relatorio.php" class="text-success text-decoration-none small">Ver Relatório de Evolução →</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>