-- Adicionar campo capacidade na tabela mesas se não existir
ALTER TABLE mesas ADD COLUMN IF NOT EXISTS capacidade INT DEFAULT 4;


