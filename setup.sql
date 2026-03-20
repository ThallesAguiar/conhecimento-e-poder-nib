-- ═══════════════════════════════════════════════════════════
--  setup.sql — Banco de Dados: Quiz Informática Básica
-- ═══════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS `quiz-nib`
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `quiz-nib`;

CREATE TABLE IF NOT EXISTS pontuacoes (
    id                   INT UNSIGNED       AUTO_INCREMENT PRIMARY KEY,
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
    INDEX idx_rank  (tema, acertos DESC, erros ASC, tempo_total_segundos ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migração: Adicionar coluna tema se não existir
SET @exists = (SELECT COUNT(*) FROM information_schema.columns 
               WHERE table_schema = 'quiz-nib' AND table_name = 'pontuacoes' AND column_name = 'tema');
SET @sql = IF(@exists = 0, 'ALTER TABLE pontuacoes ADD COLUMN tema VARCHAR(50) NOT NULL DEFAULT "geral" AFTER turma', 'SELECT "Coluna tema ja existe"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SELECT 'Setup de temas concluído!' AS status;
