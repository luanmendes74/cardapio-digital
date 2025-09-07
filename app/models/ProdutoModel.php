<?php
class ProdutoModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Retorna todos os produtos de um estabelecimento específico
    public function findByEstabelecimentoId($estabelecimento_id)
    {
        $this->db->query('SELECT * FROM produtos WHERE estabelecimento_id = :id ORDER BY ordem, nome ASC');
        $this->db->bind(':id', $estabelecimento_id);
        return $this->db->resultSet();
    }

    // --- NOVO MÉTODO ADICIONADO ---
    // Retorna todos os produtos de uma categoria específica
    public function findByCategoriaId($categoria_id)
    {
        $this->db->query('SELECT * FROM produtos WHERE categoria_id = :id AND disponivel = 1 ORDER BY ordem, nome ASC');
        $this->db->bind(':id', $categoria_id);
        return $this->db->resultSet();
    }

    // Retorna todos os produtos de um estabelecimento disponíveis para delivery
    public function findByEstabelecimentoIdDelivery($estabelecimento_id)
    {
        $this->db->query('SELECT * FROM produtos WHERE estabelecimento_id = :id AND disponivel = 1 AND disponivel_delivery = 1 ORDER BY ordem, nome ASC');
        $this->db->bind(':id', $estabelecimento_id);
        return $this->db->resultSet();
    }

    // Encontra um produto pelo seu ID
    public function findById($id)
    {
        $this->db->query('SELECT * FROM produtos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Adiciona um novo produto à base de dados
    public function add($dados)
    {
        $this->db->query('INSERT INTO produtos (estabelecimento_id, categoria_id, nome, descricao, preco, imagem, disponivel, disponivel_delivery, destaque) VALUES (:est_id, :cat_id, :nome, :desc, :preco, :img, :disp, :disp_delivery, :dest)');
        $this->db->bind(':est_id', $dados['estabelecimento_id']);
        $this->db->bind(':cat_id', $dados['categoria_id']);
        $this->db->bind(':nome', $dados['nome']);
        $this->db->bind(':desc', $dados['descricao']);
        $this->db->bind(':preco', $dados['preco']);
        $this->db->bind(':img', $dados['imagem_nome']);
        $this->db->bind(':disp', $dados['disponivel']);
        $this->db->bind(':disp_delivery', $dados['disponivel_delivery']);
        $this->db->bind(':dest', $dados['destaque']);
        return $this->db->execute();
    }

    // Atualiza um produto existente
    public function update($dados)
    {
        $this->db->query('UPDATE produtos SET nome = :nome, descricao = :desc, preco = :preco, categoria_id = :cat_id, imagem = :img, disponivel = :disp, disponivel_delivery = :disp_delivery, destaque = :dest WHERE id = :id');
        $this->db->bind(':id', $dados['id']);
        $this->db->bind(':nome', $dados['nome']);
        $this->db->bind(':desc', $dados['descricao']);
        $this->db->bind(':preco', $dados['preco']);
        $this->db->bind(':cat_id', $dados['categoria_id']);
        $this->db->bind(':img', $dados['imagem_nome']);
        $this->db->bind(':disp', $dados['disponivel']);
        $this->db->bind(':disp_delivery', $dados['disponivel_delivery']);
        $this->db->bind(':dest', $dados['destaque']);
        return $this->db->execute();
    }

    // Apaga um produto da base de dados
    public function delete($id)
    {
        $this->db->query('DELETE FROM produtos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}

