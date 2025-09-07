<?php

class ConfiguracaoDeliveryModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Buscar configuração de delivery por estabelecimento
    public function findByEstabelecimentoId($estabelecimento_id)
    {
        $this->db->query("
            SELECT * FROM configuracao_delivery 
            WHERE estabelecimento_id = :estabelecimento_id
        ");
        
        $this->db->bind(':estabelecimento_id', $estabelecimento_id);
        
        return $this->db->single();
    }

    // Criar configuração de delivery
    public function create($dados)
    {
        $this->db->query("
            INSERT INTO configuracao_delivery 
            (estabelecimento_id, ativo, taxa_entrega, valor_minimo, tempo_estimado, areas_entrega, observacoes)
            VALUES 
            (:estabelecimento_id, :ativo, :taxa_entrega, :valor_minimo, :tempo_estimado, :areas_entrega, :observacoes)
        ");
        
        $this->db->bind(':estabelecimento_id', $dados['estabelecimento_id']);
        $this->db->bind(':ativo', $dados['ativo'] ?? 1);
        $this->db->bind(':taxa_entrega', $dados['taxa_entrega'] ?? 0);
        $this->db->bind(':valor_minimo', $dados['valor_minimo'] ?? 0);
        $this->db->bind(':tempo_estimado', $dados['tempo_estimado'] ?? 30);
        $this->db->bind(':areas_entrega', $dados['areas_entrega'] ?? '');
        $this->db->bind(':observacoes', $dados['observacoes'] ?? '');
        
        return $this->db->execute();
    }

    // Atualizar configuração de delivery
    public function update($id, $dados)
    {
        $this->db->query("
            UPDATE configuracao_delivery 
            SET ativo = :ativo, 
                taxa_entrega = :taxa_entrega, 
                valor_minimo = :valor_minimo, 
                tempo_estimado = :tempo_estimado, 
                areas_entrega = :areas_entrega, 
                observacoes = :observacoes,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");
        
        $this->db->bind(':id', $id);
        $this->db->bind(':ativo', $dados['ativo'] ?? 1);
        $this->db->bind(':taxa_entrega', $dados['taxa_entrega'] ?? 0);
        $this->db->bind(':valor_minimo', $dados['valor_minimo'] ?? 0);
        $this->db->bind(':tempo_estimado', $dados['tempo_estimado'] ?? 30);
        $this->db->bind(':areas_entrega', $dados['areas_entrega'] ?? '');
        $this->db->bind(':observacoes', $dados['observacoes'] ?? '');
        
        return $this->db->execute();
    }

    // Ativar/desativar delivery
    public function toggleAtivo($estabelecimento_id, $ativo)
    {
        $this->db->query("
            UPDATE configuracao_delivery 
            SET ativo = :ativo, updated_at = CURRENT_TIMESTAMP
            WHERE estabelecimento_id = :estabelecimento_id
        ");
        
        $this->db->bind(':estabelecimento_id', $estabelecimento_id);
        $this->db->bind(':ativo', $ativo ? 1 : 0);
        
        return $this->db->execute();
    }

    // Verificar se delivery está ativo
    public function isDeliveryAtivo($estabelecimento_id)
    {
        $config = $this->findByEstabelecimentoId($estabelecimento_id);
        return $config && $config->ativo == 1;
    }

    // Obter configuração padrão
    public function getConfiguracaoPadrao()
    {
        return [
            'ativo' => 1,
            'taxa_entrega' => 5.00,
            'valor_minimo' => 20.00,
            'tempo_estimado' => 30,
            'areas_entrega' => '',
            'observacoes' => ''
        ];
    }
}

