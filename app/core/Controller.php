<?php
/*
 * Controller Base
 * Esta é a classe principal que todos os outros controllers irão estender.
 * Ela fornece métodos úteis como carregar models e views.
 */
class Controller
{
    /**
     * Carrega e instancia um Model.
     * Este método confia no autoloader (definido em bootstrap.php) para encontrar e incluir o ficheiro do model.
     * @param string $model O nome do Model a ser carregado (ex: 'EstabelecimentoModel')
     * @return object A instância do Model
     */
    public function model($model)
    {
        // O autoloader (em bootstrap.php) tratará de carregar o ficheiro.
        // Apenas precisamos de verificar se a classe existe antes de a instanciar.
        if (class_exists($model)) {
            return new $model();
        } else {
            // Se o model não for encontrado, é um erro crítico que deve parar a aplicação.
            die("Erro Crítico: O Model '{$model}' não foi encontrado. Verifique se o ficheiro 'app/models/{$model}.php' existe e se o nome da classe está correto.");
        }
    }

    /**
     * Carrega uma View.
     * @param string $view O caminho para a view dentro da pasta 'app/views' (ex: 'estabelecimento/cardapio')
     * @param array $dados Dados a serem passados para a view
     */
    public function view($view, $dados = [])
    {
        // Constrói o caminho completo para o ficheiro da view
        $viewFile = '../app/views/' . $view . '.php';

        // Verifica se o ficheiro da view realmente existe
        if (file_exists($viewFile)) {
            // Extrai os dados para que possam ser usados como variáveis na view
            extract($dados);
            
            require_once $viewFile;
        } else {
            // Se a view não existir, mostra uma mensagem de erro clara.
            die("Erro Crítico: A View '{$view}' não foi encontrada no caminho '{$viewFile}'.");
        }
    }
}