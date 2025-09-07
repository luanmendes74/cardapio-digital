<?php
class PlanoModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function findAll()
    {
        $this->db->query('SELECT * FROM planos ORDER BY preco_mensal ASC');
        return $this->db->resultSet();
    }

    public function findById($id)
    {
        $this->db->query('SELECT * FROM planos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function add($dados)
    {
        $this->db->query('INSERT INTO planos (nome, preco_mensal, limite_produtos, limite_mesas, recursos) VALUES (:nome, :preco, :produtos, :mesas, :recursos)');
        $this->db->bind(':nome', $dados['nome']);
        $this->db->bind(':preco', $dados['preco_mensal']);
        $this->db->bind(':produtos', $dados['limite_produtos']);
        $this->db->bind(':mesas', $dados['limite_mesas']);
        $this->db->bind(':recursos', $dados['recursos']);
        return $this->db->execute();
    }

    public function update($dados)
    {
        $this->db->query('UPDATE planos SET nome = :nome, preco_mensal = :preco, limite_produtos = :produtos, limite_mesas = :mesas, recursos = :recursos WHERE id = :id');
        $this->db->bind(':id', $dados['id']);
        $this->db->bind(':nome', $dados['nome']);
        $this->db->bind(':preco', $dados['preco_mensal']);
        $this->db->bind(':produtos', $dados['limite_produtos']);
        $this->db->bind(':mesas', $dados['limite_mesas']);
        $this->db->bind(':recursos', $dados['recursos']);
        return $this->db->execute();
    }

    public function delete($id)
    {
        $this->db->query('DELETE FROM planos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
