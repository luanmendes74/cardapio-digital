<?php
class EstabelecimentoModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    
    public function add($dados)
    {
        $this->db->query('INSERT INTO estabelecimentos (nome, subdominio, email, ativo) VALUES (:nome, :subdominio, :email, 1)');
        $this->db->bind(':nome', $dados['nome']);
        $this->db->bind(':subdominio', $dados['subdominio']);
        $this->db->bind(':email', $dados['admin_email']);
        
        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    // --- LÓGICA CORRIGIDA AQUI ---
    // Verifica se um email de estabelecimento já existe
    public function findByEmail($email)
    {
        $this->db->query('SELECT email FROM estabelecimentos WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();

        // Se o método single retornar um objeto (encontrou o email), retorna true.
        // Se não encontrar nada, retorna false.
        if($row){
            return true;
        } else {
            return false;
        }
    }

    public function update($dados)
    {
        $this->db->query("UPDATE estabelecimentos SET nome = :nome, subdominio = :subdominio WHERE id = :id");
        $this->db->bind(':id', $dados['id']);
        $this->db->bind(':nome', $dados['nome']);
        $this->db->bind(':subdominio', $dados['subdominio']);
        return $this->db->execute();
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM estabelecimentos WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function findAll()
    {
        $this->db->query('SELECT * FROM estabelecimentos ORDER BY nome ASC');
        return $this->db->resultSet();
    }

    public function findBySubdomain($subdomain)
    {
        $this->db->query('SELECT * FROM estabelecimentos WHERE subdominio = :subdominio AND ativo = 1');
        $this->db->bind(':subdominio', $subdomain);
        return $this->db->single();
    }

    public function findById($id)
    {
        $this->db->query("SELECT * FROM estabelecimentos WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function changeStatus($id, $status)
    {
        $this->db->query("UPDATE estabelecimentos SET ativo = :status WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    public function updateConfiguracoes($dados)
    {
        $this->db->query("UPDATE estabelecimentos SET nome = :nome, telefone = :telefone, endereco = :endereco, logo = :logo, descricao_curta = :descricao_curta, whatsapp = :whatsapp, instagram = :instagram, facebook = :facebook, cor_primaria = :cor_primaria, cor_secundaria = :cor_secundaria, cor_texto_header = :cor_texto_header, cor_botao_pedido = :cor_botao_pedido WHERE id = :id");
        $this->db->bind(':id', $dados['id']);
        $this->db->bind(':nome', $dados['nome']);
        $this->db->bind(':telefone', $dados['telefone']);
        $this->db->bind(':endereco', $dados['endereco']);
        $this->db->bind(':logo', $dados['logo']);
        $this->db->bind(':descricao_curta', $dados['descricao_curta']);
        $this->db->bind(':whatsapp', $dados['whatsapp']);
        $this->db->bind(':instagram', $dados['instagram']);
        $this->db->bind(':facebook', $dados['facebook']);
        $this->db->bind(':cor_primaria', $dados['cor_primaria']);
        $this->db->bind(':cor_secundaria', $dados['cor_secundaria']);
        $this->db->bind(':cor_texto_header', $dados['cor_texto_header']);
        $this->db->bind(':cor_botao_pedido', $dados['cor_botao_pedido']);
        return $this->db->execute();
    }
}

