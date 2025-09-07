<?php
// Script para testar a conexão com o banco de dados
// Execute este arquivo no navegador: http://cardapio.local/teste_banco.php

// Carrega as configurações
require_once '../config/database.php';

echo "<h2>Teste de Conexão com o Banco de Dados</h2>";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Conexão com o banco de dados estabelecida com sucesso!</p>";
    
    // Verificar se a tabela usuarios_estabelecimento existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'usuarios_estabelecimento'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Tabela 'usuarios_estabelecimento' existe</p>";
        
        // Verificar se a coluna 'nivel' existe
        $stmt = $pdo->query("SHOW COLUMNS FROM usuarios_estabelecimento LIKE 'nivel'");
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✅ Coluna 'nivel' existe na tabela usuarios_estabelecimento</p>";
        } else {
            echo "<p style='color: red;'>❌ Coluna 'nivel' NÃO existe na tabela usuarios_estabelecimento</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Tabela 'usuarios_estabelecimento' NÃO existe</p>";
    }
    
    // Verificar outras tabelas importantes
    $tabelas = ['estabelecimentos', 'produtos', 'categorias', 'mesas', 'pedidos'];
    foreach ($tabelas as $tabela) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$tabela'");
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✅ Tabela '$tabela' existe</p>";
        } else {
            echo "<p style='color: red;'>❌ Tabela '$tabela' NÃO existe</p>";
        }
    }
    
    // Testar uma query simples
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM estabelecimentos");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>📊 Total de estabelecimentos: " . $result['total'] . "</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Erro de conexão: " . $e->getMessage() . "</p>";
    echo "<p>Verifique se:</p>";
    echo "<ul>";
    echo "<li>O XAMPP está rodando</li>";
    echo "<li>O MySQL está ativo</li>";
    echo "<li>O banco 'cardapio_saas_db' existe</li>";
    echo "<li>As credenciais estão corretas</li>";
    echo "</ul>";
}

echo "<p><a href='http://cardapio.local/executar_atualizacao_banco.php'>Executar Atualização do Banco</a></p>";
echo "<p><a href='http://cardapio.local/admin'>Ir para o Dashboard</a></p>";
?>
