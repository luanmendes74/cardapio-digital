<?php
require_once '../app/core/Database.php';

try {
    $db = new Database();
    
    echo "Verificando estrutura da tabela mesas...\n";
    
    // Verificar se o campo capacidade já existe
    $db->query("SHOW COLUMNS FROM mesas LIKE 'capacidade'");
    $result = $db->resultSet();
    
    if (empty($result)) {
        echo "Campo 'capacidade' não existe. Adicionando...\n";
        
        // Adicionar o campo capacidade
        $db->query("ALTER TABLE mesas ADD COLUMN capacidade INT DEFAULT 4 AFTER numero");
        $db->execute();
        
        echo "Campo 'capacidade' adicionado com sucesso!\n";
        
        // Atualizar registros existentes com valor padrão
        $db->query("UPDATE mesas SET capacidade = 4 WHERE capacidade IS NULL");
        $db->execute();
        
        echo "Registros existentes atualizados com capacidade padrão (4).\n";
        
    } else {
        echo "Campo 'capacidade' já existe.\n";
    }
    
    // Verificar a estrutura final
    echo "\nEstrutura atual da tabela mesas:\n";
    $db->query("DESCRIBE mesas");
    $result = $db->resultSet();
    
    foreach($result as $row) {
        echo "- " . $row->Field . " (" . $row->Type . ")\n";
    }
    
    echo "\n✅ Tabela mesas atualizada com sucesso!\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>


