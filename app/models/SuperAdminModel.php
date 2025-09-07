<?php
class SuperAdminModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Procura um super admin pelo email e verifica a senha
    public function login($email, $senha)
    {
        $this->db->query('SELECT * FROM super_admins WHERE email = :email');
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

