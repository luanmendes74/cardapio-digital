<?php
require_once '../app/core/Database.php';

try {
    $db = new Database();
    
    echo "Verificando e adicionando campos de configuração na tabela estabelecimentos...\n";
    
    // Campos necessários para configurações
    $campos = [
        'descricao_curta' => 'VARCHAR(255) DEFAULT NULL',
        'telefone' => 'VARCHAR(20) DEFAULT NULL',
        'endereco' => 'VARCHAR(255) DEFAULT NULL',
        'whatsapp' => 'VARCHAR(20) DEFAULT NULL',
        'instagram' => 'VARCHAR(100) DEFAULT NULL',
        'facebook' => 'VARCHAR(100) DEFAULT NULL',
        'cor_primaria' => 'VARCHAR(7) DEFAULT "#dc2626"',
        'cor_texto_header' => 'VARCHAR(7) DEFAULT "#FFFFFF"',
        'cor_secundaria' => 'VARCHAR(7) DEFAULT "#1d4ed8"',
        'cor_botao_pedido' => 'VARCHAR(7) DEFAULT "#FFA500"',
        'logo' => 'VARCHAR(255) DEFAULT NULL'
    ];
    
    foreach ($campos as $campo => $definicao) {
        // Verificar se o campo já existe
        $db->query("SHOW COLUMNS FROM estabelecimentos LIKE '$campo'");
        $result = $db->resultSet();
        
        if (empty($result)) {
            echo "Adicionando campo '$campo'...\n";
            $db->query("ALTER TABLE estabelecimentos ADD COLUMN $campo $definicao");
            $db->execute();
            echo "Campo '$campo' adicionado com sucesso!\n";
        } else {
            echo "Campo '$campo' já existe.\n";
        }
    }
    
    // Verificar a estrutura final
    echo "\nEstrutura atual da tabela estabelecimentos:\n";
    $db->query("DESCRIBE estabelecimentos");
    $result = $db->resultSet();
    
    foreach($result as $row) {
        echo "- " . $row->Field . " (" . $row->Type . ")\n";
    }
    
    echo "\n✅ Campos de configuração adicionados com sucesso!\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>


