<?php

class Estabelecimento extends Controller
{
    // Propriedades para guardar as instâncias dos models
    private $estabelecimentoModel;
    private $categoriaModel;
    private $produtoModel;

    public function __construct()
    {
        // Carrega os models necessários assim que o controller é iniciado
        $this->estabelecimentoModel = $this->model('EstabelecimentoModel');
        $this->categoriaModel = $this->model('CategoriaModel');
        $this->produtoModel = $this->model('ProdutoModel');
    }

    // Método principal que é executado para exibir o cardápio
    public function index($subdomain = '')
    {
        // 1. Encontra o estabelecimento pelo subdomínio (ex: 'pizzaria-top')
        $estabelecimento = $this->estabelecimentoModel->findBySubdomain($subdomain);

        // Se o estabelecimento não for encontrado, mostra uma página de erro 404
        if (!$estabelecimento) {
            $this->view('errors/404');
            exit();
        }
        
        // 2. Busca as categorias do estabelecimento encontrado
        $categorias = $this->categoriaModel->findByEstabelecimentoId($estabelecimento->id);

        // 3. Para cada categoria, busca os produtos correspondentes
        $categoriasComProdutos = [];
        if($categorias){
            foreach ($categorias as $categoria) {
                // Adiciona uma nova propriedade 'produtos' a cada objeto de categoria
                $categoria->produtos = $this->produtoModel->findByCategoriaId($categoria->id);
                $categoriasComProdutos[] = $categoria;
            }
        }

        // 4. Prepara todos os dados para serem enviados para a View
        $dados = [
            'estabelecimento' => $estabelecimento,
            'categoriasComProdutos' => $categoriasComProdutos
        ];

        // 5. Carrega a view do cardápio mobile, passando todos os dados
        $this->view('estabelecimento/cardapio_mobile', $dados);
    }
}

