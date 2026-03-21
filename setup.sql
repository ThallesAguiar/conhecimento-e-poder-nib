-- ═══════════════════════════════════════════════════════════
--  setup.sql — Banco de Dados: Quiz Informática Básica
-- ═══════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS `quiz-nib`
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `quiz-nib`;

CREATE TABLE IF NOT EXISTS alunos (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(100) NOT NULL,
    turma       VARCHAR(30)  NOT NULL,
    criado_em   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY  idx_nome_turma (nome, turma)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pontuacoes (
    id                   INT UNSIGNED       AUTO_INCREMENT PRIMARY KEY,
    aluno_id             INT UNSIGNED       NULL,
    nome                 VARCHAR(100)       NOT NULL,
    turma                VARCHAR(30)        NOT NULL DEFAULT 'sem_turma',
    tema                 VARCHAR(50)        NOT NULL DEFAULT 'geral',
    acertos              TINYINT UNSIGNED   NOT NULL DEFAULT 0,
    erros                TINYINT UNSIGNED   NOT NULL DEFAULT 0,
    tempo_total_segundos SMALLINT UNSIGNED  NOT NULL DEFAULT 0,
    criado_em 				 TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    INDEX idx_tema  (tema),
    INDEX idx_turma (turma),
    INDEX idx_data  (criado_em),
    INDEX idx_aluno (aluno_id),
    CONSTRAINT fk_pontuacao_aluno FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS aluno_perfil (
    aluno_id      INT UNSIGNED PRIMARY KEY,
    idade         TINYINT UNSIGNED,
    curso_faculdade VARCHAR(100),
    frequencia    VARCHAR(50),
    uso_principal VARCHAR(50),
    nivel_declarado VARCHAR(50),
    habilidades   TEXT, -- O que ele marcou que sabe fazer
    dificuldades  TEXT,
    objetivo      TEXT,
    criado_em     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_perfil_aluno FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migração: Adicionar coluna tema se não existir
SET @exists = (SELECT COUNT(*) FROM information_schema.columns 
               WHERE table_schema = 'quiz-nib' AND table_name = 'pontuacoes' AND column_name = 'tema');
SET @sql = IF(@exists = 0, 'ALTER TABLE pontuacoes ADD COLUMN tema VARCHAR(50) NOT NULL DEFAULT "geral" AFTER turma', 'SELECT "Coluna tema ja existe"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SELECT 'Setup de temas concluído!' AS status;
