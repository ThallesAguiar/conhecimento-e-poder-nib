<?php
header('Content-Type: application/json');
require_once 'db.php';

$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$action = $_GET['action'] ?? '';

try {
    if ($action === 'entrar') {
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("SELECT id FROM sessoes_quiz WHERE codigo = ? AND status != 'encerrada'");
        $stmt->execute([$data['codigo']]);
        $sessao = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$sessao) { echo json_encode(['sucesso' => false, 'erro' => 'Sala não encontrada.']); exit; }

        $stmt = $pdo->prepare("INSERT INTO jogadores_sessao (sessao_id, nome, avatar) VALUES (?, ?, ?)");
        $stmt->execute([$sessao['id'], $data['nome'], $data['avatar'] ?? '👤']);
        echo json_encode(['sucesso' => true, 'jogador_id' => $pdo->lastInsertId(), 'sessao_id' => $sessao['id']]);
    }
    elseif ($action === 'status') {
        $jogador_id = $_GET['jogador_id'];
        $sessao_id = $_GET['sessao_id'];
        $last_index = isset($_GET['last_index']) ? (int)$_GET['last_index'] : -1;

        $stmt = $pdo->prepare("SELECT status, indice_pergunta_atual FROM sessoes_quiz WHERE id = ?");
        $stmt->execute([$sessao_id]);
        $sessao = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT ataque_recebido, atacante_nome FROM jogadores_sessao WHERE id = ?");
        $stmt->execute([$jogador_id]);
        $jogador = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retorna ataque e limpa APENAS se estiver mudando de pergunta (last_index < atual)
        $ataque = $jogador['ataque_recebido'];
        $quem = $jogador['atacante_nome'];
        
        if ($ataque && (int)$sessao['indice_pergunta_atual'] > $last_index) {
            $pdo->prepare("UPDATE jogadores_sessao SET ataque_recebido = NULL, atacante_nome = NULL WHERE id = ?")->execute([$jogador_id]);
        }

        echo json_encode(['sucesso' => true, 'sessao' => $sessao, 'ataque' => $ataque, 'quem_atacou' => $quem, 'encerrada' => ($sessao['status'] === 'encerrada')]);
    }
    elseif ($action === 'listar_oponentes') {
        $stmt = $pdo->prepare("SELECT id, nome FROM jogadores_sessao WHERE sessao_id = ? AND id != ?");
        $stmt->execute([$_GET['sessao_id'], $_GET['jogador_id']]);
        echo json_encode(['sucesso' => true, 'jogadores' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }
    elseif ($action === 'atacar') {
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("UPDATE jogadores_sessao SET ataque_recebido = ?, atacante_nome = ? WHERE id = ?");
        $stmt->execute([$data['tipo'], $data['meu_nome'], $data['alvo_id']]);
        echo json_encode(['sucesso' => true]);
    }
    elseif ($action === 'responder') {
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("UPDATE jogadores_sessao SET pontuacao = pontuacao + ? WHERE id = ?");
        $stmt->execute([$data['pontos'], $data['jogador_id']]);
        echo json_encode(['sucesso' => true]);
    }
} catch (Exception $e) { echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]); }
