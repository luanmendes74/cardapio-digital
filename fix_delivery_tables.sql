-- Script para corrigir as tabelas de delivery
-- Execute este script no phpMyAdmin ou no seu cliente MySQL

-- 1. Adicionar campos para delivery na tabela pedidos (se não existirem)
ALTER TABLE pedidos 
ADD COLUMN IF NOT EXISTS cliente_nome VARCHAR(150) NULL AFTER mesa_id,
ADD COLUMN IF NOT EXISTS cliente_telefone VARCHAR(20) NULL AFTER cliente_nome,
ADD COLUMN IF NOT EXISTS endereco_entrega TEXT NULL AFTER cliente_telefone,
ADD COLUMN IF NOT EXISTS bairro VARCHAR(100) NULL AFTER endereco_entrega,
ADD COLUMN IF NOT EXISTS cidade VARCHAR(100) NULL AFTER bairro,
ADD COLUMN IF NOT EXISTS cep VARCHAR(10) NULL AFTER cidade,
ADD COLUMN IF NOT EXISTS taxa_entrega DECIMAL(10,2) DEFAULT 0.00 AFTER cep,
ADD COLUMN IF NOT EXISTS tempo_estimado INT NULL AFTER taxa_entrega;

-- 1.1. Adicionar campo disponivel_delivery na tabela produtos
ALTER TABLE produtos 
ADD COLUMN IF NOT EXISTS disponivel_delivery TINYINT(1) DEFAULT 1 AFTER disponivel;

-- 1.2. Adicionar campo preco_total na tabela pedido_itens (se não existir)
ALTER TABLE pedido_itens 
ADD COLUMN IF NOT EXISTS preco_total DECIMAL(10,2) DEFAULT 0.00 AFTER preco_unitario;

-- 2. Atualizar enum de tipo para incluir delivery
ALTER TABLE pedidos 
MODIFY COLUMN tipo ENUM('mesa','delivery') NOT NULL DEFAULT 'mesa';

-- 3. Criar tabela para configurações de delivery
CREATE TABLE IF NOT EXISTS configuracao_delivery (
    id INT NOT NULL AUTO_INCREMENT,
    estabelecimento_id INT NOT NULL,
    ativo TINYINT(1) DEFAULT 1,
    taxa_entrega DECIMAL(10,2) DEFAULT 0.00,
    valor_minimo_pedido DECIMAL(10,2) DEFAULT 0.00,
    tempo_estimado_min INT DEFAULT 30,
    tempo_estimado_max INT DEFAULT 60,
    bairros_atendidos TEXT NULL,
    horario_funcionamento TEXT NULL,
    observacoes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY estabelecimento_id (estabelecimento_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Inserir configurações padrão para os estabelecimentos existentes
INSERT INTO configuracao_delivery (estabelecimento_id, ativo, taxa_entrega, valor_minimo_pedido, tempo_estimado_min, tempo_estimado_max, bairros_atendidos, horario_funcionamento)
SELECT 
    id as estabelecimento_id,
    1 as ativo,
    5.00 as taxa_entrega,
    20.00 as valor_minimo_pedido,
    30 as tempo_estimado_min,
    60 as tempo_estimado_max,
    'Centro, Zona Sul, Zona Norte' as bairros_atendidos,
    'Segunda a Sexta: 11h às 22h, Sábado e Domingo: 11h às 23h' as horario_funcionamento
FROM estabelecimentos
WHERE id NOT IN (SELECT estabelecimento_id FROM configuracao_delivery);

-- 5. Verificar se as tabelas foram criadas corretamente
SELECT 'Tabela configuracao_delivery criada com sucesso!' as status;
SELECT COUNT(*) as total_configuracoes FROM configuracao_delivery;
SELECT COUNT(*) as total_estabelecimentos FROM estabelecimentos;
