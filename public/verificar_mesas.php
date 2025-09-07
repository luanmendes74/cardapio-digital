<?php
require_once '../app/core/Database.php';

try {
    $db = new Database();
    $db->query('DESCRIBE mesas');
    $result = $db->resultSet();
    
    echo "Estrutura da tabela mesas:\n";
    foreach($result as $row) {
        echo $row->Field . ' - ' . $row->Type . "\n";
    }
    
    // Verificar se existe o campo capacidade
    $hasCapacidade = false;
    foreach($result as $row) {
        if ($row->Field === 'capacidade') {
            $hasCapacidade = true;
            break;
        }
    }
    
    if (!$hasCapacidade) {
        echo "\nCampo 'capacidade' não existe. Adicionando...\n";
        $db->query('ALTER TABLE mesas ADD COLUMN capacidade INT DEFAULT 4');
        $db->execute();
        echo "Campo 'capacidade' adicionado com sucesso!\n";
    } else {
        echo "\nCampo 'capacidade' já existe.\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>


