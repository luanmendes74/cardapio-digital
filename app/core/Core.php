<?php
/*
 * Classe Core (Roteador Principal da Aplicação)
 * Adaptado para funcionar com rotas baseadas em caminho (ex: /pizzaria-top).
 */
class Core
{
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();

        // Se a URL estiver vazia, carrega a página inicial
        if (empty($url[0])) {
            $this->carregarController('Pages');
            return;
        }

        // Verifica se o primeiro segmento da URL é um Controller conhecido (ex: 'admin', 'SuperAdmin', 'pedido', 'delivery')
        if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            $this->currentController = ucwords($url[0]);
            unset($url[0]);
        } else {
            // Se não for um controller, assume que é o slug de um estabelecimento
            $this->currentController = 'Estabelecimento';
        }

        $this->carregarController($this->currentController, $url);
    }
    
    private function carregarController($controllerName, $url = []) {
        require_once '../app/controllers/' . $controllerName . '.php';
        $this->currentController = new $controllerName;

        // Verifica o método (segundo segmento da URL)
        $methodToCall = $url[1] ?? 'index';
        if (isset($url[1]) && method_exists($this->currentController, $url[1])) {
            $this->currentMethod = $url[1];
            unset($url[1]);
        } else {
            $this->currentMethod = 'index';
        }
        
        // Pega os parâmetros restantes
        $this->params = $url ? array_values($url) : [];
        
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }
    
    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}

