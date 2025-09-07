<?php
// Script para verificar e criar tabelas necessárias
// Acesse: http://cardapio.local/verificar_tabelas.php

require_once '../config/database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html><html lang='pt-br'><head><title>Verificação de Tabelas</title></head><body>";
echo "<h2>Verificação de Tabelas do Banco de Dados</h2>";

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    echo "<p style='color: green;'>✅ Conexão com o banco de dados estabelecida!</p>";

    // Verificar se a tabela configuracao_delivery existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'configuracao_delivery'");
    $tableExists = $stmt->fetch();

    if (!$tableExists) {
        echo "<p style='color: orange;'>⚠️ Tabela 'configuracao_delivery' não existe. Criando...</p>";
        
        $createTable = "
        CREATE TABLE configuracao_delivery (
            id INT AUTO_INCREMENT PRIMARY KEY,
            estabelecimento_id INT NOT NULL,
            ativo TINYINT(1) DEFAULT 1,
            taxa_entrega DECIMAL(10,2) DEFAULT 5.00,
            valor_minimo DECIMAL(10,2) DEFAULT 20.00,
            tempo_estimado INT DEFAULT 30 COMMENT 'Tempo estimado em minutos',
            areas_entrega TEXT NULL,
            observacoes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (estabelecimento_id) REFERENCES estabelecimentos(id) ON DELETE CASCADE,
            UNIQUE KEY unique_estabelecimento_delivery (estabelecimento_id)
        )";
        
        $pdo->exec($createTable);
        echo "<p style='color: green;'>✅ Tabela 'configuracao_delivery' criada com sucesso!</p>";
    } else {
        echo "<p style='color: green;'>✅ Tabela 'configuracao_delivery' já existe!</p>";
    }

    // Verificar se há configurações para estabelecimentos existentes
    $stmt = $pdo->query("
        SELECT e.id, e.nome, cd.id as config_id 
        FROM estabelecimentos e 
        LEFT JOIN configuracao_delivery cd ON e.id = cd.estabelecimento_id
    ");
    $estabelecimentos = $stmt->fetchAll();

    echo "<h3>Estabelecimentos e Configurações de Delivery:</h3>";
    echo "<ul>";
    
    foreach ($estabelecimentos as $est) {
        if ($est->config_id) {
            echo "<li style='color: green;'>✅ {$est->nome} - Configuração OK</li>";
        } else {
            echo "<li style='color: orange;'>⚠️ {$est->nome} - Sem configuração de delivery</li>";
            
            // Criar configuração padrão
            $insertConfig = "
            INSERT INTO configuracao_delivery 
            (estabelecimento_id, ativo, taxa_entrega, valor_minimo, tempo_estimado, areas_entrega, observacoes)
            VALUES 
            (:estabelecimento_id, 1, 5.00, 20.00, 30, '', '')
            ";
            
            $stmt = $pdo->prepare($insertConfig);
            $stmt->bindParam(':estabelecimento_id', $est->id);
            $stmt->execute();
            
            echo "<li style='color: green;'>✅ Configuração padrão criada para {$est->nome}</li>";
        }
    }
    
    echo "</ul>";

    // Listar todas as tabelas
    echo "<h3>Todas as tabelas no banco de dados:</h3>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>{$table}</li>";
    }
    echo "</ul>";

    echo "<p style='color: green;'><strong>✅ Verificação concluída com sucesso!</strong></p>";
    echo "<p><a href='http://cardapio.local/admin'>← Voltar ao Dashboard</a></p>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Erro de conexão com o banco de dados:</p>";
    echo "<p>{$e->getMessage()}</p>";
}

echo "</body></html>";
?>


