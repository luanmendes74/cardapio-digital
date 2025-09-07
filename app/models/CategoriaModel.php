<?php
class CategoriaModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function findByEstabelecimentoId($estabelecimento_id)
    {
        $this->db->query("SELECT * FROM categorias WHERE estabelecimento_id = :id ORDER BY ordem ASC, nome ASC");
        $this->db->bind(':id', $estabelecimento_id);
        return $this->db->resultSet();
    }

    public function findById($id)
    {
        $this->db->query("SELECT * FROM categorias WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function add($dados)
    {
        $this->db->query("INSERT INTO categorias (estabelecimento_id, nome, descricao, ordem) VALUES (:est_id, :nome, :desc, :ordem)");
        $this->db->bind(':est_id', $dados['estabelecimento_id']);
        $this->db->bind(':nome', $dados['nome']);
        $this->db->bind(':desc', $dados['descricao']);
        $this->db->bind(':ordem', $dados['ordem']);
        return $this->db->execute();
    }

    public function update($dados)
    {
        $this->db->query("UPDATE categorias SET nome = :nome, descricao = :desc, ordem = :ordem WHERE id = :id");
        $this->db->bind(':id', $dados['id']);
        $this->db->bind(':nome', $dados['nome']);
        $this->db->bind(':desc', $dados['descricao']);
        $this->db->bind(':ordem', $dados['ordem']);
        return $this->db->execute();
    }

    public function delete($id)
    {
        // ATENÇÃO: Apagar uma categoria também apaga os produtos associados!
        // Esta é uma regra de negócio importante.
        $this->db->query("DELETE FROM produtos WHERE categoria_id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();

        $this->db->query("DELETE FROM categorias WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}

