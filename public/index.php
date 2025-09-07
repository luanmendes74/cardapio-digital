<?php

// --- MODO DE DEPURAÇÃO ---
// Durante o desenvolvimento, é útil ter os erros visíveis.
// Em produção, isto deve ser desativado.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Carrega o ficheiro principal de inicialização (bootstrap)
// Este ficheiro contém configurações e o autoload de classes.
require_once '../app/bootstrap.php';

// Instancia a classe Core (o nosso roteador) para iniciar a aplicação
$init = new Core();
