<?php
class Delivery extends Controller
{
    private $produtoModel;
    private $categoriaModel;
    private $estabelecimentoModel;
    private $configuracaoDeliveryModel;
    private $db;
    private $estabelecimento;

    public function __construct()
    {
        $this->produtoModel = $this->model('ProdutoModel');
        $this->categoriaModel = $this->model('CategoriaModel');
        $this->estabelecimentoModel = $this->model('EstabelecimentoModel');
        $this->configuracaoDeliveryModel = $this->model('ConfiguracaoDeliveryModel');
        $this->db = new Database();
        
        // Carregar dados do estabelecimento baseado no subdomínio ou parâmetro
        $this->carregarEstabelecimento();
    }
    
    private function carregarEstabelecimento()
    {
        // Tentar carregar por subdomínio primeiro
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $subdomain = explode('.', $host)[0];
        
        if ($subdomain !== 'cardapio' && $subdomain !== 'localhost') {
            $this->estabelecimento = $this->estabelecimentoModel->findBySubdomain($subdomain);
        }
        
        // Se não encontrou por subdomínio, tentar por parâmetro
        if (!$this->estabelecimento && isset($_GET['estabelecimento'])) {
            $this->estabelecimento = $this->estabelecimentoModel->findBySubdomain($_GET['estabelecimento']);
        }
        
        // Se ainda não encontrou, usar o primeiro estabelecimento (para desenvolvimento)
        if (!$this->estabelecimento) {
            $this->db->query('SELECT * FROM estabelecimentos LIMIT 1');
            $this->estabelecimento = $this->db->single();
        }
    }
    
    public function index()
    {
        if (!$this->estabelecimento) {
            $this->view('delivery/erro', ['mensagem' => 'Estabelecimento não encontrado']);
            return;
        }
        
        // Carregar configurações de delivery
        $configuracao = $this->configuracaoDeliveryModel->findByEstabelecimentoId($this->estabelecimento->id);
        
        // Carregar categorias e produtos (igual ao cardápio da mesa)
        $categorias = $this->categoriaModel->findByEstabelecimentoId($this->estabelecimento->id);
        
        // Para cada categoria, busca os produtos correspondentes (filtrando apenas os disponíveis para delivery)
        $categoriasComProdutos = [];
        if($categorias){
            foreach ($categorias as $categoria) {
                $produtos = $this->produtoModel->findByCategoriaId($categoria->id);
                // Filtrar apenas produtos disponíveis para delivery
                $produtosDelivery = array_filter($produtos, function($produto) {
                    return $produto->disponivel_delivery == 1 && $produto->disponivel == 1;
                });
                $categoria->produtos = $produtosDelivery;
                $categoriasComProdutos[] = $categoria;
            }
        }
        
        $dados = [
            'titulo' => 'Cardápio Delivery - ' . $this->estabelecimento->nome,
            'estabelecimento' => $this->estabelecimento,
            'configuracao' => $configuracao,
            'categoriasComProdutos' => $categoriasComProdutos
        ];
        
        $this->view('delivery/cardapio_mobile', $dados);
    }
    
    public function adicionarPedido()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit();
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $estabelecimento_id = $_POST['estabelecimento_id'] ?? null;
        $cliente_nome = trim($_POST['cliente_nome'] ?? '');
        $cliente_telefone = trim($_POST['cliente_telefone'] ?? '');
        $cliente_endereco = trim($_POST['cliente_endereco'] ?? '');
        $observacoes = trim($_POST['observacoes'] ?? '');
        $itens_json = $_POST['itens'] ?? '[]';
        $itens = json_decode($itens_json, true);

        if (!$estabelecimento_id || empty($itens)) {
            echo json_encode(['success' => false, 'message' => 'Dados do pedido inválidos']);
            exit();
        }

        if (empty($cliente_nome) || empty($cliente_telefone) || empty($cliente_endereco)) {
            echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos obrigatórios']);
            exit();
        }

        // Calcular total
        $total = 0;
        $itens_pedido = [];
        foreach ($itens as $item) {
            $subtotal = $item['preco'] * $item['quantidade'];
            $total += $subtotal;
            $itens_pedido[] = [
                'produto_id' => $item['produto_id'],
                'quantidade' => $item['quantidade'],
                'preco_unitario' => $item['preco'],
                'preco_total' => $subtotal,
                'observacoes' => $item['observacoes'] ?? ''
            ];
        }

        // Adicionar taxa de entrega
        $configuracao = $this->configuracaoDeliveryModel->findByEstabelecimentoId($estabelecimento_id);
        $taxa_entrega = $configuracao->taxa_entrega ?? 0;
        $total_com_taxa = $total + $taxa_entrega;

        // Criar pedido
        $dados_pedido = [
            'estabelecimento_id' => $estabelecimento_id,
            'mesa_id' => null,
            'tipo' => 'delivery',
            'status' => 'recebido',
            'total' => $total_com_taxa,
            'taxa_entrega' => $taxa_entrega,
            'nome_cliente' => $cliente_nome,
            'telefone_cliente' => $cliente_telefone,
            'endereco_cliente' => $cliente_endereco,
            'observacoes' => $observacoes,
            'itens' => $itens_pedido
        ];

        $pedido_id = $this->pedidoModel->addPedidoDelivery($dados_pedido);
        if ($pedido_id) {
            echo json_encode(['success' => true, 'pedido_id' => $pedido_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao processar pedido']);
        }
    }
    
    public function sucesso($pedidoId)
    {
        $this->db->query('SELECT * FROM pedidos WHERE id = :id');
        $this->db->bind(':id', $pedidoId);
        $pedido = $this->db->single();
        
        if (!$pedido) {
            header('location: ' . URL_BASE . '/delivery');
            exit();
        }
        
        $dados = [
            'titulo' => 'Pedido Realizado - ' . $this->estabelecimento->nome,
            'estabelecimento' => $this->estabelecimento,
            'pedido' => $pedido
        ];
        
        $this->view('delivery/sucesso', $dados);
    }
}
?>



