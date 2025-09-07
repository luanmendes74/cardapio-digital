<?php
/*
 * Classe de Conexão com a Base de Dados (PDO)
 * Conecta-se à base de dados, prepara e executa as queries.
 */
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh; // Database Handler
    private $stmt; // Statement
    private $error;

    public function __construct(){
        // Configura o DSN (Data Source Name)
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5 // Adiciona um tempo limite de 5 segundos
        );

        // Cria a instância do PDO
        try{
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e){
            $this->error = $e->getMessage();
            // Se houver um erro, termina a aplicação e mostra a mensagem
            die('Erro de Conexão com a Base de Dados: ' . $this->error);
        }
    }

    // Prepara a statement com a query
    public function query($sql){
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Faz o bind dos valores
    public function bind($param, $value, $type = null){
        if(is_null($type)){
            switch(true){
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Executa a statement preparada
    public function execute(){
        return $this->stmt->execute();
    }

    // Retorna um conjunto de resultados (array de objetos)
    public function resultSet(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Retorna um único resultado (objeto)
    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Retorna o número de linhas
    public function rowCount(){
        return $this->stmt->rowCount();
    }
    
    // --- MÉTODO ADICIONADO ---
    // Retorna o ID do último registo inserido
    public function lastInsertId(){
        return $this->dbh->lastInsertId();
    }
}

