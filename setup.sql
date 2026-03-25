-- ═══════════════════════════════════════════════════════════
--  setup.sql — Banco de Dados: Quiz Informática Básica
-- ═══════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS `quiz-nib`
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `quiz-nib`;

-- 1. Tabela de Alunos
CREATE TABLE IF NOT EXISTS alunos (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(100) NOT NULL,
    turma       VARCHAR(30)  NOT NULL,
    criado_em   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY  idx_nome_turma (nome, turma)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabela de Pontuações
CREATE TABLE IF NOT EXISTS pontuacoes (
    id                   INT UNSIGNED        AUTO_INCREMENT PRIMARY KEY,
    aluno_id             INT UNSIGNED        NULL,
    nome                 VARCHAR(100)        NOT NULL,
    turma                VARCHAR(30)         NOT NULL DEFAULT 'sem_turma',
    tema                 VARCHAR(50)         NOT NULL DEFAULT 'geral',
    acertos              TINYINT UNSIGNED    NOT NULL DEFAULT 0,
    erros                TINYINT UNSIGNED    NOT NULL DEFAULT 0,
    tempo_total_segundos SMALLINT UNSIGNED   NOT NULL DEFAULT 0,
    criado_em            TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    INDEX idx_tema  (tema),
    INDEX idx_turma (turma),
    INDEX idx_data  (criado_em),
    INDEX idx_aluno (aluno_id),
    CONSTRAINT fk_pontuacao_aluno FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabela de Perfil
CREATE TABLE IF NOT EXISTS aluno_perfil (
    aluno_id      INT UNSIGNED PRIMARY KEY,
    idade         TINYINT UNSIGNED,
    curso_faculdade VARCHAR(100),
    frequencia    VARCHAR(50),
    uso_principal VARCHAR(50),
    nivel_declarado VARCHAR(50),
    habilidades   TEXT,
    dificuldades  TEXT,
    objetivo      TEXT,
    criado_em     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_perfil_aluno FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Multiplayer: Sessões
CREATE TABLE IF NOT EXISTS `sessoes_quiz` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `codigo` CHAR(6) NOT NULL UNIQUE,
  `tema` VARCHAR(50) DEFAULT 'geral',
  `status` ENUM('aguardando', 'ativa', 'encerrada') DEFAULT 'aguardando',
  `indice_pergunta_atual` INT DEFAULT -1,
  `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Multiplayer: Jogadores
CREATE TABLE IF NOT EXISTS `jogadores_sessao` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sessao_id` INT NOT NULL,
  `nome` VARCHAR(100) NOT NULL,
  `avatar` VARCHAR(50) DEFAULT '👤',
  `pontuacao` INT DEFAULT 0,
  `ausente` TINYINT(1) DEFAULT 0,
  `ataque_recebido` VARCHAR(20) DEFAULT NULL,
  `atacante_nome` VARCHAR(100) DEFAULT NULL,
  `ultimo_ping` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`sessao_id`) REFERENCES `sessoes_quiz`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ═══════════════════════════════════════════════════════════
-- BLOCO DE MIGRAÇÃO (Evita erros se as colunas já existirem)
-- ═══════════════════════════════════════════════════════════

-- Migração: Coluna 'tema' na tabela pontuacoes
SET @exists = (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'pontuacoes' AND column_name = 'tema');
SET @sql = IF(@exists = 0, 'ALTER TABLE pontuacoes ADD COLUMN tema VARCHAR(50) NOT NULL DEFAULT "geral" AFTER turma', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Migração: Coluna 'ataque_recebido' na tabela jogadores_sessao
SET @exists_ataque = (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'jogadores_sessao' AND column_name = 'ataque_recebido');
SET @sql_ataque = IF(@exists_ataque = 0, 'ALTER TABLE jogadores_sessao ADD COLUMN ataque_recebido VARCHAR(20) DEFAULT NULL AFTER pontuacao', 'SELECT 1');
PREPARE stmt1 FROM @sql_ataque; EXECUTE stmt1; DEALLOCATE PREPARE stmt1;

-- Migração: Coluna 'atacante_nome' na tabela jogadores_sessao
SET @exists_quem = (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'jogadores_sessao' AND column_name = 'atacante_nome');
SET @sql_quem = IF(@exists_quem = 0, 'ALTER TABLE jogadores_sessao ADD COLUMN atacante_nome VARCHAR(100) DEFAULT NULL AFTER ataque_recebido', 'SELECT 1');
PREPARE stmt2 FROM @sql_quem; EXECUTE stmt2; DEALLOCATE PREPARE stmt2;

-- Migração: Coluna 'ausente' na tabela jogadores_sessao
SET @exists_ausente = (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'jogadores_sessao' AND column_name = 'ausente');
SET @sql_ausente = IF(@exists_ausente = 0, 'ALTER TABLE jogadores_sessao ADD COLUMN ausente TINYINT(1) DEFAULT 0 AFTER pontuacao', 'SELECT 1');
PREPARE stmt3 FROM @sql_ausente; EXECUTE stmt3; DEALLOCATE PREPARE stmt3;

SELECT 'Banco de dados atualizado com sucesso!' AS status;