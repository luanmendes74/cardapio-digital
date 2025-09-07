<?php
class UsuarioModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    
    public function add($dados)
    {
        $dados['admin_senha'] = password_hash($dados['admin_senha'], PASSWORD_DEFAULT);
        $nivel = $dados['nivel'] ?? 'admin'; // Default para admin
        $this->db->query('INSERT INTO usuarios_estabelecimento (estabelecimento_id, nome, email, senha, tipo, nivel, ativo) VALUES (:est_id, :nome, :email, :senha, "admin", :nivel, 1)');
        $this->db->bind(':est_id', $dados['estabelecimento_id']);
        $this->db->bind(':nome', $dados['admin_nome']);
        $this->db->bind(':email', $dados['admin_email']);
        $this->db->bind(':senha', $dados['admin_senha']);
        $this->db->bind(':nivel', $nivel);
        return $this->db->execute();
    }

    // --- NOVO MÉTODO ---
    // Apaga todos os utilizadores associados a um estabelecimento
    public function deleteByEstabelecimentoId($id)
    {
        $this->db->query('DELETE FROM usuarios_estabelecimento WHERE estabelecimento_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function login($email, $senha)
    {
        $this->db->query('SELECT *, COALESCE(nivel, "cozinha") as nivel FROM usuarios_estabelecimento WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        if ($row) {
            $hashed_password = $row->senha;
            if (password_verify($senha, $hashed_password)) {
                return $row;
            }
        }
        return false;
    }
}

