<?php
class PedidoModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function criar($dados)
    {
        $query = 'INSERT INTO pedidos (estabelecimento_id, mesa_id, tipo, status, total, observacoes';
        $values = '(:est_id, :mesa_id, :tipo, :status, :total, :obs';
        
        // Adicionar campos específicos do delivery se existirem
        if (isset($dados['cliente_nome'])) {
            $query .= ', cliente_nome, cliente_telefone, endereco_entrega, bairro, cidade, cep, taxa_entrega, tempo_estimado';
            $values .= ', :cliente_nome, :cliente_telefone, :endereco_entrega, :bairro, :cidade, :cep, :taxa_entrega, :tempo_estimado';
        }
        
        $query .= ') VALUES ' . $values . ')';
        
        $this->db->query($query);
        $this->db->bind(':est_id', $dados['estabelecimento_id']);
        $this->db->bind(':mesa_id', $dados['mesa_id'] ?? null);
        $this->db->bind(':tipo', $dados['tipo'] ?? 'mesa');
        $this->db->bind(':status', 'recebido');
        $this->db->bind(':total', $dados['total']);
        $this->db->bind(':obs', $dados['observacoes'] ?? '');
        
        // Bind dos campos de delivery se existirem
        if (isset($dados['cliente_nome'])) {
            $this->db->bind(':cliente_nome', $dados['cliente_nome']);
            $this->db->bind(':cliente_telefone', $dados['cliente_telefone']);
            $this->db->bind(':endereco_entrega', $dados['endereco_entrega']);
            $this->db->bind(':bairro', $dados['bairro'] ?? '');
            $this->db->bind(':cidade', $dados['cidade'] ?? '');
            $this->db->bind(':cep', $dados['cep'] ?? '');
            $this->db->bind(':taxa_entrega', $dados['taxa_entrega'] ?? 0);
            $this->db->bind(':tempo_estimado', $dados['tempo_estimado'] ?? null);
        }
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Retorna todos os pedidos de um estabelecimento que não estão finalizados
    public function findByEstabelecimentoId($id)
    {
        $this->db->query("
            SELECT p.*, m.numero as numero_mesa 
            FROM pedidos p
            LEFT JOIN mesas m ON p.mesa_id = m.id
            WHERE p.estabelecimento_id = :id AND p.status != 'finalizado'
            ORDER BY p.created_at DESC
        ");
        $this->db->bind(':id', $id);
        $pedidos = $this->db->resultSet();

        // Para cada pedido, busca os seus itens
        foreach ($pedidos as $pedido) {
            $this->db->query("
                SELECT pi.*, pr.nome as nome_produto 
                FROM pedido_itens pi
                JOIN produtos pr ON pi.produto_id = pr.id
                WHERE pi.pedido_id = :pedido_id
            ");
            $this->db->bind(':pedido_id', $pedido->id);
            $pedido->itens = $this->db->resultSet();
        }

        return $pedidos;
    }

    // Atualiza o status de um pedido
    public function updateStatus($id, $status)
    {
        $this->db->query("UPDATE pedidos SET status = :status WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    // Adiciona pedido de mesa
    public function addPedidoMesa($dados)
    {
        try {
            $this->db->beginTransaction();

            // Inserir pedido
            $this->db->query("
                INSERT INTO pedidos (estabelecimento_id, mesa_id, tipo, status, total, nome_cliente, telefone_cliente, observacoes, created_at) 
                VALUES (:estabelecimento_id, :mesa_id, :tipo, :status, :total, :nome_cliente, :telefone_cliente, :observacoes, NOW())
            ");
            $this->db->bind(':estabelecimento_id', $dados['estabelecimento_id']);
            $this->db->bind(':mesa_id', $dados['mesa_id']);
            $this->db->bind(':tipo', $dados['tipo']);
            $this->db->bind(':status', $dados['status']);
            $this->db->bind(':total', $dados['total']);
            $this->db->bind(':nome_cliente', $dados['nome_cliente']);
            $this->db->bind(':telefone_cliente', $dados['telefone_cliente']);
            $this->db->bind(':observacoes', $dados['observacoes']);

            if (!$this->db->execute()) {
                throw new Exception('Erro ao inserir pedido');
            }

            $pedido_id = $this->db->lastInsertId();

            // Inserir itens do pedido
            foreach ($dados['itens'] as $item) {
                $this->db->query("
                    INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unitario, preco_total, observacoes) 
                    VALUES (:pedido_id, :produto_id, :quantidade, :preco_unitario, :preco_total, :observacoes)
                ");
                $this->db->bind(':pedido_id', $pedido_id);
                $this->db->bind(':produto_id', $item['produto_id']);
                $this->db->bind(':quantidade', $item['quantidade']);
                $this->db->bind(':preco_unitario', $item['preco_unitario']);
                $this->db->bind(':preco_total', $item['preco_total']);
                $this->db->bind(':observacoes', $item['observacoes']);

                if (!$this->db->execute()) {
                    throw new Exception('Erro ao inserir item do pedido');
                }
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    // Adiciona pedido de delivery
    public function addPedidoDelivery($dados)
    {
        try {
            $this->db->beginTransaction();

            // Inserir pedido
            $this->db->query("
                INSERT INTO pedidos (estabelecimento_id, mesa_id, tipo, status, total, taxa_entrega, nome_cliente, telefone_cliente, endereco_cliente, observacoes, created_at) 
                VALUES (:estabelecimento_id, :mesa_id, :tipo, :status, :total, :taxa_entrega, :nome_cliente, :telefone_cliente, :endereco_cliente, :observacoes, NOW())
            ");
            $this->db->bind(':estabelecimento_id', $dados['estabelecimento_id']);
            $this->db->bind(':mesa_id', $dados['mesa_id']);
            $this->db->bind(':tipo', $dados['tipo']);
            $this->db->bind(':status', $dados['status']);
            $this->db->bind(':total', $dados['total']);
            $this->db->bind(':taxa_entrega', $dados['taxa_entrega']);
            $this->db->bind(':nome_cliente', $dados['nome_cliente']);
            $this->db->bind(':telefone_cliente', $dados['telefone_cliente']);
            $this->db->bind(':endereco_cliente', $dados['endereco_cliente']);
            $this->db->bind(':observacoes', $dados['observacoes']);

            if (!$this->db->execute()) {
                throw new Exception('Erro ao inserir pedido');
            }

            $pedido_id = $this->db->lastInsertId();

            // Inserir itens do pedido
            foreach ($dados['itens'] as $item) {
                $this->db->query("
                    INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unitario, preco_total, observacoes) 
                    VALUES (:pedido_id, :produto_id, :quantidade, :preco_unitario, :preco_total, :observacoes)
                ");
                $this->db->bind(':pedido_id', $pedido_id);
                $this->db->bind(':produto_id', $item['produto_id']);
                $this->db->bind(':quantidade', $item['quantidade']);
                $this->db->bind(':preco_unitario', $item['preco_unitario']);
                $this->db->bind(':preco_total', $item['preco_total']);
                $this->db->bind(':observacoes', $item['observacoes']);

                if (!$this->db->execute()) {
                    throw new Exception('Erro ao inserir item do pedido');
                }
            }

            $this->db->commit();
            return $pedido_id;

        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
}

