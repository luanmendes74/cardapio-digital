<?php
// Script para executar as atualizações do banco de dados
// Execute este arquivo no navegador: http://cardapio.local/executar_atualizacao_banco.php

// Configurações do banco
$host = 'localhost';
$dbname = 'cardapio_saas_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Executando atualizações do banco de dados...</h2>";
    
    // 1. Adicionar coluna de nível de usuário
    echo "<p>1. Adicionando coluna 'nivel' na tabela usuarios_estabelecimento...</p>";
    try {
        $pdo->exec("ALTER TABLE usuarios_estabelecimento ADD COLUMN nivel ENUM('admin', 'cozinha') NOT NULL DEFAULT 'cozinha'");
        echo "<span style='color: green;'>✓ Coluna 'nivel' adicionada com sucesso!</span><br>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "<span style='color: orange;'>⚠ Coluna 'nivel' já existe, pulando...</span><br>";
        } else {
            echo "<span style='color: red;'>✗ Erro: " . $e->getMessage() . "</span><br>";
        }
    }
    
    // 2. Atualizar usuários existentes
    echo "<p>2. Atualizando usuários existentes para nível admin...</p>";
    try {
        $stmt = $pdo->prepare("UPDATE usuarios_estabelecimento SET nivel = 'admin' WHERE id > 0");
        $stmt->execute();
        $affected = $stmt->rowCount();
        echo "<span style='color: green;'>✓ $affected usuários atualizados para nível admin!</span><br>";
    } catch (PDOException $e) {
        echo "<span style='color: red;'>✗ Erro: " . $e->getMessage() . "</span><br>";
    }
    
    // 3. Criar tabela de relatórios diários
    echo "<p>3. Criando tabela relatorios_diarios...</p>";
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS relatorios_diarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            estabelecimento_id INT NOT NULL,
            data_relatorio DATE NOT NULL,
            total_pedidos INT DEFAULT 0,
            total_mesa INT DEFAULT 0,
            total_delivery INT DEFAULT 0,
            faturamento_total DECIMAL(10,2) DEFAULT 0.00,
            faturamento_mesa DECIMAL(10,2) DEFAULT 0.00,
            faturamento_delivery DECIMAL(10,2) DEFAULT 0.00,
            ticket_medio DECIMAL(10,2) DEFAULT 0.00,
            taxa_entrega_total DECIMAL(10,2) DEFAULT 0.00,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (estabelecimento_id) REFERENCES estabelecimentos(id) ON DELETE CASCADE,
            UNIQUE KEY unique_estabelecimento_data (estabelecimento_id, data_relatorio)
        )");
        echo "<span style='color: green;'>✓ Tabela relatorios_diarios criada com sucesso!</span><br>";
    } catch (PDOException $e) {
        echo "<span style='color: red;'>✗ Erro: " . $e->getMessage() . "</span><br>";
    }
    
    // 4. Criar tabela de relatórios mensais
    echo "<p>4. Criando tabela relatorios_mensais...</p>";
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS relatorios_mensais (
            id INT AUTO_INCREMENT PRIMARY KEY,
            estabelecimento_id INT NOT NULL,
            ano INT NOT NULL,
            mes INT NOT NULL,
            total_pedidos INT DEFAULT 0,
            total_mesa INT DEFAULT 0,
            total_delivery INT DEFAULT 0,
            faturamento_total DECIMAL(10,2) DEFAULT 0.00,
            faturamento_mesa DECIMAL(10,2) DEFAULT 0.00,
            faturamento_delivery DECIMAL(10,2) DEFAULT 0.00,
            ticket_medio DECIMAL(10,2) DEFAULT 0.00,
            dias_funcionamento INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (estabelecimento_id) REFERENCES estabelecimentos(id) ON DELETE CASCADE,
            UNIQUE KEY unique_estabelecimento_ano_mes (estabelecimento_id, ano, mes)
        )");
        echo "<span style='color: green;'>✓ Tabela relatorios_mensais criada com sucesso!</span><br>";
    } catch (PDOException $e) {
        echo "<span style='color: red;'>✗ Erro: " . $e->getMessage() . "</span><br>";
    }
    
    // 5. Criar tabela de produtos mais vendidos
    echo "<p>5. Criando tabela relatorios_produtos_vendidos...</p>";
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS relatorios_produtos_vendidos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            estabelecimento_id INT NOT NULL,
            produto_id INT NOT NULL,
            data_relatorio DATE NOT NULL,
            quantidade_vendida INT DEFAULT 0,
            valor_total DECIMAL(10,2) DEFAULT 0.00,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (estabelecimento_id) REFERENCES estabelecimentos(id) ON DELETE CASCADE,
            FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
            UNIQUE KEY unique_produto_data (produto_id, data_relatorio)
        )");
        echo "<span style='color: green;'>✓ Tabela relatorios_produtos_vendidos criada com sucesso!</span><br>";
    } catch (PDOException $e) {
        echo "<span style='color: red;'>✗ Erro: " . $e->getMessage() . "</span><br>";
    }
    
    // 6. Criar tabela de configurações de layout
    echo "<p>6. Criando tabela configuracoes_layout...</p>";
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS configuracoes_layout (
            id INT AUTO_INCREMENT PRIMARY KEY,
            estabelecimento_id INT NOT NULL,
            cor_primaria VARCHAR(7) DEFAULT '#3B82F6',
            cor_secundaria VARCHAR(7) DEFAULT '#1E40AF',
            cor_sucesso VARCHAR(7) DEFAULT '#10B981',
            cor_aviso VARCHAR(7) DEFAULT '#F59E0B',
            cor_erro VARCHAR(7) DEFAULT '#EF4444',
            logo_url VARCHAR(255) NULL,
            nome_estabelecimento VARCHAR(255) NOT NULL,
            tema VARCHAR(20) DEFAULT 'claro',
            fonte VARCHAR(50) DEFAULT 'Inter',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (estabelecimento_id) REFERENCES estabelecimentos(id) ON DELETE CASCADE,
            UNIQUE KEY unique_estabelecimento_layout (estabelecimento_id)
        )");
        echo "<span style='color: green;'>✓ Tabela configuracoes_layout criada com sucesso!</span><br>";
    } catch (PDOException $e) {
        echo "<span style='color: red;'>✗ Erro: " . $e->getMessage() . "</span><br>";
    }
    
    // 7. Inserir configurações padrão
    echo "<p>7. Inserindo configurações padrão de layout...</p>";
    try {
        $pdo->exec("INSERT IGNORE INTO configuracoes_layout (estabelecimento_id, nome_estabelecimento)
        SELECT id, nome FROM estabelecimentos
        WHERE id NOT IN (SELECT estabelecimento_id FROM configuracoes_layout)");
        echo "<span style='color: green;'>✓ Configurações padrão inseridas com sucesso!</span><br>";
    } catch (PDOException $e) {
        echo "<span style='color: red;'>✗ Erro: " . $e->getMessage() . "</span><br>";
    }
    
    // 8. Criar índices para performance
    echo "<p>8. Criando índices para melhor performance...</p>";
    try {
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_pedidos_estabelecimento_data ON pedidos(estabelecimento_id, created_at)");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_pedidos_status ON pedidos(status)");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_pedidos_tipo ON pedidos(tipo)");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_pedido_itens_produto ON pedido_itens(produto_id)");
        echo "<span style='color: green;'>✓ Índices criados com sucesso!</span><br>";
    } catch (PDOException $e) {
        echo "<span style='color: red;'>✗ Erro: " . $e->getMessage() . "</span><br>";
    }
    
    // 9. Adicionar colunas extras na tabela pedidos
    echo "<p>9. Adicionando colunas extras na tabela pedidos...</p>";
    try {
        $pdo->exec("ALTER TABLE pedidos ADD COLUMN IF NOT EXISTS observacoes TEXT NULL");
        $pdo->exec("ALTER TABLE pedidos ADD COLUMN IF NOT EXISTS tempo_preparo_estimado INT DEFAULT 30 COMMENT 'Tempo estimado em minutos'");
        echo "<span style='color: green;'>✓ Colunas extras adicionadas com sucesso!</span><br>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "<span style='color: orange;'>⚠ Colunas já existem, pulando...</span><br>";
        } else {
            echo "<span style='color: red;'>✗ Erro: " . $e->getMessage() . "</span><br>";
        }
    }
    
    echo "<h3 style='color: green;'>✅ Atualização do banco de dados concluída com sucesso!</h3>";
    echo "<p><a href='http://cardapio.local/admin'>← Voltar ao Dashboard</a></p>";
    
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>❌ Erro de conexão com o banco de dados:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p>Verifique se:</p>";
    echo "<ul>";
    echo "<li>O XAMPP está rodando</li>";
    echo "<li>O MySQL está ativo</li>";
    echo "<li>O banco 'cardapio_saas_db' existe</li>";
    echo "<li>As credenciais estão corretas</li>";
    echo "</ul>";
}
?>
