<?php
header('Content-Type: application/json');
require_once 'db.php';

$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    if ($action === 'criar') {
        $codigo = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $tema = $_GET['tema'] ?? 'geral';

        $stmt = $pdo->prepare("INSERT INTO sessoes_quiz (codigo, tema, status, indice_pergunta_atual) VALUES (?, ?, 'aguardando', -1)");
        $stmt->execute([$codigo, $tema]);

        echo json_encode(['sucesso' => true, 'sessao_id' => $pdo->lastInsertId(), 'codigo' => $codigo]);
    } 
    elseif ($action === 'iniciar') {
        $sessao_id = $_GET['sessao_id'];
        $stmt = $pdo->prepare("UPDATE sessoes_quiz SET status = 'ativa', indice_pergunta_atual = 0 WHERE id = ?");
        $stmt->execute([$sessao_id]);
        echo json_encode(['sucesso' => true]);
    }
    elseif ($action === 'proxima') {
        $sessao_id = $_GET['sessao_id'];
        $stmt = $pdo->prepare("UPDATE sessoes_quiz SET indice_pergunta_atual = indice_pergunta_atual + 1 WHERE id = ?");
        $stmt->execute([$sessao_id]);
        echo json_encode(['sucesso' => true]);
    }
    elseif ($action === 'encerrar') {
        $sessao_id = $_GET['sessao_id'];
        $stmt = $pdo->prepare("UPDATE sessoes_quiz SET status = 'encerrada' WHERE id = ?");
        $stmt->execute([$sessao_id]);
        echo json_encode(['sucesso' => true]);
    }
    elseif ($action === 'atacar') {
        $jogador_id = $_GET['jogador_id'];
        $tipo = $_GET['tipo']; // 'ice' ou 'gloop'
        
        $stmt = $pdo->prepare("UPDATE jogadores_sessao SET ataque_recebido = ? WHERE id = ?");
        $stmt->execute([$tipo, $jogador_id]);
        echo json_encode(['sucesso' => true]);
    }
    elseif ($action === 'status') {
        $sessao_id = $_GET['sessao_id'];
        
        // Dados da Sessão
        $stmt = $pdo->prepare("SELECT * FROM sessoes_quiz WHERE id = ?");
        $stmt->execute([$sessao_id]);
        $sessao = $stmt->fetch(PDO::FETCH_ASSOC);

        // Lista de Jogadores e Ranking em tempo real
        $stmt = $pdo->prepare("SELECT id, nome, avatar, pontuacao FROM jogadores_sessao WHERE sessao_id = ? ORDER BY pontuacao DESC");
        $stmt->execute([$sessao_id]);
        $jogadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'sucesso' => true, 
            'sessao' => $sessao, 
            'jogadores' => $jogadores
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}
