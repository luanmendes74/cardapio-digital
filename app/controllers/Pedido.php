<?php
class Pedido extends Controller
{
    private $pedidoModel;
    private $pedidoItemModel;

    public function __construct()
    {
        $this->pedidoModel = $this->model('PedidoModel');
        $this->pedidoItemModel = $this->model('PedidoItemModel');
    }

    // Método para receber e processar um novo pedido (JSON)
    public function criar()
    {
        // Garante que a requisição é do tipo POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['erro' => 'Método não permitido.']);
            exit();
        }

        // Obtém os dados JSON enviados pelo cliente
        $json = file_get_contents('php://input');
        $dados = json_decode($json);

        // Validação básica dos dados recebidos
        if (!isset($dados->mesa_id) || !isset($dados->estabelecimento_id) || empty($dados->items)) {
            http_response_code(400); // Bad Request
            echo json_encode(['erro' => 'Dados do pedido inválidos.']);
            exit();
        }

        // Cria o pedido principal na tabela 'pedidos'
        $novoPedidoId = $this->pedidoModel->criar([
            'estabelecimento_id' => $dados->estabelecimento_id,
            'mesa_id' => $dados->mesa_id,
            'total' => $dados->total,
            'observacoes' => $dados->observacoes ?? ''
        ]);

        if ($novoPedidoId) {
            // Se o pedido foi criado, insere cada item na tabela 'pedido_itens'
            foreach ($dados->items as $item) {
                $this->pedidoItemModel->criar([
                    'pedido_id' => $novoPedidoId,
                    'produto_id' => $item->id,
                    'quantidade' => $item->quantidade,
                    'preco_unitario' => $item->preco,
                    'observacoes' => $item->observacoes ?? ''
                ]);
            }
            // Retorna uma resposta de sucesso para o cliente
            http_response_code(201); // Created
            echo json_encode(['sucesso' => true, 'pedido_id' => $novoPedidoId]);
        } else {
            // Se falhar, retorna um erro
            http_response_code(500); // Internal Server Error
            echo json_encode(['erro' => 'Não foi possível registar o pedido.']);
        }
    }

    // Método para receber pedidos via FormData (cardápio)
    public function adicionar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit();
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $estabelecimento_id = $_POST['estabelecimento_id'] ?? null;
        $mesa_id = $_POST['mesa_id'] ?? null;
        $cliente_nome = trim($_POST['cliente_nome'] ?? '');
        $cliente_telefone = trim($_POST['cliente_telefone'] ?? '');
        $observacoes = trim($_POST['observacoes'] ?? '');
        $itens_json = $_POST['itens'] ?? '[]';
        $itens = json_decode($itens_json, true);

        if (!$estabelecimento_id || empty($itens)) {
            echo json_encode(['success' => false, 'message' => 'Dados do pedido inválidos']);
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

        // Criar pedido
        $dados_pedido = [
            'estabelecimento_id' => $estabelecimento_id,
            'mesa_id' => $mesa_id,
            'tipo' => 'mesa',
            'status' => 'recebido',
            'total' => $total,
            'nome_cliente' => $cliente_nome,
            'telefone_cliente' => $cliente_telefone,
            'observacoes' => $observacoes,
            'itens' => $itens_pedido
        ];

        if ($this->pedidoModel->addPedidoMesa($dados_pedido)) {
            echo json_encode(['success' => true, 'message' => 'Pedido realizado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao processar pedido']);
        }
    }

    public function sucesso($pedido_id = null)
    {
        $dados = [
            'titulo' => 'Pedido Realizado com Sucesso!',
            'pedido_id' => $pedido_id
        ];
        $this->view('pedido/sucesso', $dados);
    }
}
