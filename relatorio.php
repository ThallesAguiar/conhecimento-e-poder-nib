<?php
/**
 * relatorio.php
 * Visão do Professor: Acompanhamento de evolução e diagnóstico.
 */

require_once("./db.php");

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );

    // Busca todos os alunos, seus perfis e médias de quiz
    $sql = "
        SELECT 
            a.nome, 
            a.turma, 
            p.nivel_declarado,
            p.curso_faculdade,
            p.habilidades,
            p.dificuldades as dificult_forms,
            AVG(pt.acertos) as media_acertos,
            COUNT(pt.id) as total_quizzes,
            GROUP_CONCAT(DISTINCT pt.tema SEPARATOR ', ') as temas_feitos
        FROM alunos a
        LEFT JOIN aluno_perfil p ON a.id = p.aluno_id
        LEFT JOIN pontuacoes pt ON a.id = pt.aluno_id
        GROUP BY a.id
        ORDER BY a.turma, a.nome
    ";
    $relatorio = $pdo->query($sql)->fetchAll();

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}

function getBadgeNivel($nivel) {
    $n = strtolower($nivel);
    if (strpos($n, 'iniciante') !== false) return 'bg-danger';
    if (strpos($n, 'básico') !== false) return 'bg-warning text-dark';
    if (strpos($n, 'intermediário') !== false) return 'bg-success';
    return 'bg-secondary';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório Pedagógico - Quiz NIB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; font-family: sans-serif; }
        .header-top { background: #0d1117; color: #fff; padding: 2rem 0; margin-bottom: 2rem; }
        .card-estudo { border-left: 5px solid #5c6bc0; }
        .progress { height: 10px; }
    </style>
</head>
<body>

<div class="header-top">
    <div class="container">
        <h1>📊 Relatório de Evolução</h1>
        <p class="text-muted">Acompanhamento de Diagnóstico Inicial vs Desempenho em Aula</p>
    </div>
</div>

<div class="container mb-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">Lista de Acompanhamento</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Aluno / Turma</th>
                            <th>Nível Inicial (Forms)</th>
                            <th>Média Quiz (Real)</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($relatorio as $r): 
                            $media = $r['media_acertos'] ? round(($r['media_acertos'] / 15) * 100) : 0;
                            $desempenho = "Inativo";
                            $cor = "secondary";
                            if ($r['total_quizzes'] > 0) {
                                if ($media >= 80) { $desempenho = "Excelente"; $cor = "success"; }
                                elseif ($media >= 50) { $desempenho = "Em Evolução"; $cor = "info"; }
                                else { $desempenho = "Atenção"; $cor = "danger"; }
                            }
                        ?>
                        <tr>
                            <td>
                                <strong><?php echo $r['nome']; ?></strong><br>
                                <small class="text-muted"><?php echo $r['turma']; ?></small>
                            </td>
                            <td>
                                <span class="badge <?php echo getBadgeNivel($r['nivel_declarado']); ?>">
                                    <?php echo $r['nivel_declarado'] ?: 'Não respondeu'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar bg-<?php echo $cor; ?>" style="width: <?php echo $media; ?>%"></div>
                                    </div>
                                    <small><?php echo $media; ?>%</small>
                                </div>
                                <small class="text-muted"><?php echo $r['total_quizzes']; ?> quiz(zes) feitos</small>
                            </td>
                            <td>
                                <span class="text-<?php echo $cor; ?> fw-bold"><?php echo $desempenho; ?></span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-<?php echo md5($r['nome']); ?>">Ver Detalhes</button>
                            </td>
                        </tr>

                        <!-- Modal Detalhes -->
                        <div class="modal fade" id="modal-<?php echo md5($r['nome']); ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Dossiê: <?php echo $r['nome']; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>📌 Diagnóstico Inicial</h6>
                                                <p><strong>Curso Original:</strong><br>
                                                <span class="text-info"><?php echo $r['curso_faculdade'] ?: 'Não informado'; ?></span></p>
                                                <p><strong>Dificuldades que relatou:</strong><br>
                                                <span class="text-danger"><?php echo $r['dificult_forms'] ?: 'Nada relatado'; ?></span></p>
                                                <p><strong>Habilidades declaradas:</strong><br>
                                                <span class="text-success"><?php echo $r['habilidades'] ?: 'Nenhuma'; ?></span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>🎯 Desempenho Real</h6>
                                                <p><strong>Média de Acertos:</strong> <?php echo round($r['media_acertos'], 1); ?> de 15</p>
                                                <p><strong>Temas já concluídos:</strong><br>
                                                <span class="badge bg-primary"><?php echo $r['temas_feitos'] ?: 'Nenhum'; ?></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>