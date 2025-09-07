<?php
class PedidoItemModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Adiciona um item a um pedido existente
    public function criar($dados)
    {
        $this->db->query('INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unitario, observacoes) VALUES (:pedido_id, :produto_id, :qtd, :preco, :obs)');

        $this->db->bind(':pedido_id', $dados['pedido_id']);
        $this->db->bind(':produto_id', $dados['produto_id']);
        $this->db->bind(':qtd', $dados['quantidade']);
        $this->db->bind(':preco', $dados['preco_unitario']);
        $this->db->bind(':obs', $dados['observacoes']);

        return $this->db->execute();
    }
}
