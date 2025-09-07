<?php
class MesaModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Retorna todas as mesas de um estabelecimento específico
    public function findByEstabelecimentoId($estabelecimento_id)
    {
        $this->db->query('SELECT * FROM mesas WHERE estabelecimento_id = :id ORDER BY numero ASC');
        $this->db->bind(':id', $estabelecimento_id);
        return $this->db->resultSet();
    }

    // Encontra uma mesa pelo seu ID
    public function findById($id)
    {
        $this->db->query('SELECT * FROM mesas WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Adiciona uma nova mesa à base de dados
    public function add($dados)
    {
        // Verificar se o campo capacidade existe
        $this->db->query("SHOW COLUMNS FROM mesas LIKE 'capacidade'");
        $hasCapacidade = !empty($this->db->resultSet());
        
        if ($hasCapacidade) {
            $this->db->query('INSERT INTO mesas (estabelecimento_id, numero, capacidade, status) VALUES (:est_id, :numero, :capacidade, :status)');
            $this->db->bind(':est_id', $dados['estabelecimento_id']);
            $this->db->bind(':numero', $dados['numero']);
            $this->db->bind(':capacidade', $dados['capacidade'] ?? 4);
            $this->db->bind(':status', 'livre');
        } else {
            $this->db->query('INSERT INTO mesas (estabelecimento_id, numero, status) VALUES (:est_id, :numero, :status)');
            $this->db->bind(':est_id', $dados['estabelecimento_id']);
            $this->db->bind(':numero', $dados['numero']);
            $this->db->bind(':status', 'livre');
        }
        
        // Retorna o ID da mesa inserida para gerar o QR code
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Atualiza uma mesa existente
    public function update($dados)
    {
        // Verificar se o campo capacidade existe
        $this->db->query("SHOW COLUMNS FROM mesas LIKE 'capacidade'");
        $hasCapacidade = !empty($this->db->resultSet());
        
        if ($hasCapacidade) {
            $this->db->query('UPDATE mesas SET numero = :numero, capacidade = :capacidade WHERE id = :id');
            $this->db->bind(':id', $dados['id']);
            $this->db->bind(':numero', $dados['numero']);
            $this->db->bind(':capacidade', $dados['capacidade'] ?? 4);
        } else {
            $this->db->query('UPDATE mesas SET numero = :numero WHERE id = :id');
            $this->db->bind(':id', $dados['id']);
            $this->db->bind(':numero', $dados['numero']);
        }
        
        return $this->db->execute();
    }

    // Apaga uma mesa da base de dados
    public function delete($id)
    {
        $this->db->query('DELETE FROM mesas WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Atualiza o QR Code no banco de dados
    public function updateQrCode($id, $qrCodePath)
    {
        $this->db->query('UPDATE mesas SET qr_code = :qr_code WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':qr_code', $qrCodePath);
        return $this->db->execute();
    }
}
