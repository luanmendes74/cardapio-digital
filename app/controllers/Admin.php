<?php
class Admin extends Controller
{
    private $usuarioModel;
    private $produtoModel;
    private $categoriaModel;
    private $estabelecimentoModel;
    private $mesaModel;
    private $pedidoModel;
    private $configuracaoDeliveryModel;
    private $db;
    private $estabelecimento;

    public function __construct()
    {
        if (!isLoggedIn() && !in_array(strtolower($_GET['url'] ?? ''), ['admin/login'])) {
            header('location: ' . URL_BASE . '/admin/login');
            exit();
        }
        $this->usuarioModel = $this->model('UsuarioModel');
        $this->produtoModel = $this->model('ProdutoModel');
        $this->categoriaModel = $this->model('CategoriaModel');
        $this->estabelecimentoModel = $this->model('EstabelecimentoModel');
        $this->mesaModel = $this->model('MesaModel');
        $this->pedidoModel = $this->model('PedidoModel');
        $this->configuracaoDeliveryModel = $this->model('ConfiguracaoDeliveryModel');
        $this->db = new Database();
        if (isLoggedIn()) {
            $this->estabelecimento = $this->estabelecimentoModel->findById($_SESSION['estabelecimento_id']);
        }
    }
    
    private function getDadosBase()
    {
        return [
            'estabelecimento' => $this->estabelecimento ?? (object)['nome' => 'Estabelecimento', 'id' => 0]
        ];
    }
    
    public function index()
    {
        $dados = array_merge($this->getDadosBase(), ['titulo' => 'Dashboard']);
        $this->view('admin/dashboard', $dados);
    }
    
    public function getDashboardStats()
    {
        header('Content-Type: application/json');
        
        if (!isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['erro' => 'Não autorizado']);
            return;
        }

        try {
            // Pedidos de hoje
            $this->db->query("SELECT COUNT(*) as total FROM pedidos WHERE estabelecimento_id = :id AND DATE(created_at) = CURDATE()");
            $this->db->bind(':id', $_SESSION['estabelecimento_id']);
            $pedidos_hoje = $this->db->single()->total;

            // Pedidos de ontem para comparação
            $this->db->query("SELECT COUNT(*) as total FROM pedidos WHERE estabelecimento_id = :id AND DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
            $this->db->bind(':id', $_SESSION['estabelecimento_id']);
            $pedidos_ontem = $this->db->single()->total;

            // Total de produtos
            $this->db->query("SELECT COUNT(*) as total FROM produtos WHERE estabelecimento_id = :id");
            $this->db->bind(':id', $_SESSION['estabelecimento_id']);
            $total_produtos = $this->db->single()->total;

            // Mesas ativas
            $this->db->query("SELECT COUNT(*) as total FROM mesas WHERE estabelecimento_id = :id");
            $this->db->bind(':id', $_SESSION['estabelecimento_id']);
            $mesas_ativas = $this->db->single()->total;

            // Mesas ocupadas (com pedidos ativos)
            $this->db->query("
                SELECT COUNT(DISTINCT mesa_id) as total 
                FROM pedidos 
                WHERE estabelecimento_id = :id 
                AND status IN ('recebido', 'preparando') 
                AND mesa_id IS NOT NULL
            ");
            $this->db->bind(':id', $_SESSION['estabelecimento_id']);
            $mesas_ocupadas = $this->db->single()->total;

            // Novos pedidos (recebidos e não preparando)
            $this->db->query("
                SELECT COUNT(*) as total 
                FROM pedidos 
                WHERE estabelecimento_id = :id 
                AND status = 'recebido'
            ");
            $this->db->bind(':id', $_SESSION['estabelecimento_id']);
            $novos_pedidos = $this->db->single()->total;

            // Faturamento de hoje
            $this->db->query("SELECT COALESCE(SUM(total), 0) as total FROM pedidos WHERE estabelecimento_id = :id AND DATE(created_at) = CURDATE() AND status != 'cancelado'");
            $this->db->bind(':id', $_SESSION['estabelecimento_id']);
            $faturamento_hoje = $this->db->single()->total;

            // Pedidos recentes
            $this->db->query("
                SELECT p.*, m.numero as mesa_numero 
                FROM pedidos p 
                LEFT JOIN mesas m ON p.mesa_id = m.id 
                WHERE p.estabelecimento_id = :id 
                ORDER BY p.created_at DESC 
                LIMIT 10
            ");
            $this->db->bind(':id', $_SESSION['estabelecimento_id']);
            $pedidos_recentes = $this->db->resultSet();

            // Calcular crescimento de pedidos
            $crescimento_pedidos = 0;
            if ($pedidos_ontem > 0) {
                $crescimento_pedidos = round((($pedidos_hoje - $pedidos_ontem) / $pedidos_ontem) * 100, 1);
            } else if ($pedidos_hoje > 0) {
                $crescimento_pedidos = 100;
            }

            echo json_encode([
                'pedidos_hoje' => $pedidos_hoje,
                'pedidos_ontem' => $pedidos_ontem,
                'crescimento_pedidos' => $crescimento_pedidos,
                'total_produtos' => $total_produtos,
                'mesas_ativas' => $mesas_ativas,
                'mesas_ocupadas' => $mesas_ocupadas,
                'novos_pedidos' => $novos_pedidos,
                'faturamento_hoje' => $faturamento_hoje,
                'pedidos_recentes' => $pedidos_recentes
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro interno do servidor']);
        }
    }
    
    public function pedidos()
    {
        $dados = array_merge($this->getDadosBase(), ['titulo' => 'Painel de Pedidos']);
        $this->view('admin/pedidos', $dados);
    }
    
    public function getPedidosAjax()
    {
        header('Content-Type: application/json');
        
        if (!isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['erro' => 'Não autorizado']);
            return;
        }

        try {
            $this->db->query("
                SELECT p.*, m.numero as mesa_numero 
                FROM pedidos p 
                LEFT JOIN mesas m ON p.mesa_id = m.id 
                WHERE p.estabelecimento_id = :id 
                AND p.tipo != 'delivery'
                ORDER BY p.created_at ASC
            ");
            $this->db->bind(':id', $_SESSION['estabelecimento_id']);
            $pedidos = $this->db->resultSet();

            echo json_encode($pedidos);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro interno do servidor']);
        }
    }
    
    public function atualizarStatusPedido($id)
    {
        header('Content-Type: application/json');
        
        if (!isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['erro' => 'Não autorizado']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $novo_status = $input['status'] ?? '';

        if (empty($novo_status)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Status não informado']);
            return;
        }

        try {
            $this->db->query("UPDATE pedidos SET status = :status WHERE id = :id AND estabelecimento_id = :estabelecimento_id");
            $this->db->bind(':status', $novo_status);
            $this->db->bind(':id', $id);
            $this->db->bind(':estabelecimento_id', $_SESSION['estabelecimento_id']);
            
            if ($this->db->execute()) {
                echo json_encode(['sucesso' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['erro' => 'Erro ao atualizar status']);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro interno do servidor']);
        }
    }
    
    public function pedidosDelivery()
    {
        $dados = array_merge($this->getDadosBase(), ['titulo' => 'Pedidos Delivery']);
        $this->view('admin/pedidos_delivery', $dados);
    }

    public function mudarStatusPedido($pedido_id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit();
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $novo_status = $input['status'] ?? '';

        if (empty($novo_status)) {
            echo json_encode(['success' => false, 'message' => 'Status inválido']);
            exit();
        }

        $this->db->query("UPDATE pedidos SET status = :status WHERE id = :id AND estabelecimento_id = :estabelecimento_id");
        $this->db->bind(':status', $novo_status);
        $this->db->bind(':id', $pedido_id);
        $this->db->bind(':estabelecimento_id', $_SESSION['estabelecimento_id']);

        if ($this->db->execute()) {
            echo json_encode(['success' => true, 'message' => 'Status atualizado com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar status']);
        }
    }
    
    public function getPedidosDeliveryAjax()
    {
        header('Content-Type: application/json');
        
        if (!isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['erro' => 'Não autorizado']);
            return;
        }

        try {
            $this->db->query("
                SELECT p.*, m.numero as mesa_numero 
                FROM pedidos p 
                LEFT JOIN mesas m ON p.mesa_id = m.id 
                WHERE p.estabelecimento_id = :id 
                AND p.tipo = 'delivery'
                ORDER BY p.created_at ASC
            ");
            $this->db->bind(':id', $_SESSION['estabelecimento_id']);
            $pedidos = $this->db->resultSet();

            echo json_encode($pedidos);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro interno do servidor']);
        }
    }
    
    public function produtos()
    {
        $produtos = $this->produtoModel->findByEstabelecimentoId($_SESSION['estabelecimento_id']);
        $dados = array_merge($this->getDadosBase(), [
            'titulo' => 'Gestão de Produtos',
            'produtos' => $produtos
        ]);
        $this->view('admin/produtos', $dados);
    }
    
    public function categorias()
    {
        $categorias = $this->categoriaModel->findByEstabelecimentoId($_SESSION['estabelecimento_id']);
        $dados = array_merge($this->getDadosBase(), [
            'titulo' => 'Gestão de Categorias',
            'categorias' => $categorias
        ]);
        $this->view('admin/categorias', $dados);
    }
    
    public function mesas()
    {
        $mesas = $this->mesaModel->findByEstabelecimentoId($_SESSION['estabelecimento_id']);
        $dados = array_merge($this->getDadosBase(), [
            'titulo' => 'Gestão de Mesas',
            'mesas' => $mesas
        ]);
        $this->view('admin/mesas', $dados);
    }
    
    public function configuracoes()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            
            $dados = [
                'nome' => trim($_POST['nome'] ?? ''),
                'descricao_curta' => trim($_POST['descricao_curta'] ?? ''),
                'telefone' => trim($_POST['telefone'] ?? ''),
                'endereco' => trim($_POST['endereco'] ?? ''),
                'whatsapp' => trim($_POST['whatsapp'] ?? ''),
                'instagram' => trim($_POST['instagram'] ?? ''),
                'facebook' => trim($_POST['facebook'] ?? ''),
                'cor_primaria' => trim($_POST['cor_primaria'] ?? '#dc2626'),
                'cor_texto_header' => trim($_POST['cor_texto_header'] ?? '#FFFFFF'),
                'cor_secundaria' => trim($_POST['cor_secundaria'] ?? '#1d4ed8'),
                'cor_botao_pedido' => trim($_POST['cor_botao_pedido'] ?? '#FFA500'),
                'estabelecimento_id' => $_SESSION['estabelecimento_id'],
                'nome_err' => '',
                'logo_err' => ''
            ];
            
            // Validação
            if (empty($dados['nome'])) {
                $dados['nome_err'] = 'Por favor, insira o nome do estabelecimento.';
            }
            
            // Processar upload de logo
            if (!empty($_FILES['logo']['name'])) {
                $uploadResult = $this->processarUploadLogo($_FILES['logo']);
                if ($uploadResult['success']) {
                    $dados['logo'] = $uploadResult['filename'];
                } else {
                    $dados['logo_err'] = $uploadResult['error'];
                }
            }
            
            if (empty($dados['nome_err']) && empty($dados['logo_err'])) {
                // Atualizar configurações no banco
                if ($this->atualizarConfiguracoes($dados)) {
                    $_SESSION['success_message'] = 'Configurações salvas com sucesso!';
                    header('location: ' . URL_BASE . '/admin/configuracoes');
                    exit();
                } else {
                    $dados['error_message'] = 'Erro ao salvar configurações.';
                }
            }
        } else {
            // Carregar configurações atuais
            $configuracoes = $this->carregarConfiguracoes();
            $dados = array_merge($this->getDadosBase(), [
                'titulo' => 'Configurações',
                'nome' => $configuracoes['nome'] ?? '',
                'descricao_curta' => $configuracoes['descricao_curta'] ?? '',
                'telefone' => $configuracoes['telefone'] ?? '',
                'endereco' => $configuracoes['endereco'] ?? '',
                'whatsapp' => $configuracoes['whatsapp'] ?? '',
                'instagram' => $configuracoes['instagram'] ?? '',
                'facebook' => $configuracoes['facebook'] ?? '',
                'cor_primaria' => $configuracoes['cor_primaria'] ?? '#dc2626',
                'cor_texto_header' => $configuracoes['cor_texto_header'] ?? '#FFFFFF',
                'cor_secundaria' => $configuracoes['cor_secundaria'] ?? '#1d4ed8',
                'cor_botao_pedido' => $configuracoes['cor_botao_pedido'] ?? '#FFA500',
                'logo_atual' => $configuracoes['logo'] ?? '',
                'nome_err' => '',
                'logo_err' => ''
            ]);
        }
        
        $this->view('admin/configuracoes', $dados);
    }
    
    public function delivery()
    {
        $configuracao = $this->configuracaoDeliveryModel->findByEstabelecimentoId($_SESSION['estabelecimento_id']);
        $dados = array_merge($this->getDadosBase(), [
            'titulo' => 'Configurações de Delivery',
            'configuracao' => $configuracao
        ]);
        $this->view('admin/delivery', $dados);
    }
    
    public function login()
    {
        if (isLoggedIn()) {
            header('location: ' . URL_BASE . '/admin');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            
            $dados = [
                'email' => trim($_POST['email']),
                'senha' => trim($_POST['senha']),
                'email_err' => '',
                'senha_err' => ''
            ];
            
            if (empty($dados['email'])) {
                $dados['email_err'] = 'Por favor, insira o email.';
            }
            
            if (empty($dados['senha'])) {
                $dados['senha_err'] = 'Por favor, insira a senha.';
            }
            
            if (empty($dados['email_err']) && empty($dados['senha_err'])) {
                $usuario = $this->usuarioModel->login($dados['email'], $dados['senha']);
                
                if ($usuario) {
                    $this->createUserSession($usuario);
                    header('location: ' . URL_BASE . '/admin');
                    exit();
                } else {
                    $dados['senha_err'] = 'Email ou senha incorretos.';
                }
            }
            
            $this->view('admin/login', $dados);
        } else {
            $dados = [
                'email' => '',
                'senha' => '',
                'email_err' => '',
                'senha_err' => ''
            ];
            $this->view('admin/login', $dados);
        }
    }
    
    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_nome']);
        unset($_SESSION['estabelecimento_id']);
        session_destroy();
        header('location: ' . URL_BASE . '/admin/login');
        exit();
    }
    
    private function createUserSession($usuario)
    {
        $_SESSION['user_id'] = $usuario->id;
        $_SESSION['user_nome'] = $usuario->nome;
        $_SESSION['estabelecimento_id'] = $usuario->estabelecimento_id;
    }
    
    // Métodos para Produtos
    public function addproduto()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            
            $dados = [
                'nome' => trim($_POST['nome']),
                'descricao' => trim($_POST['descricao']),
                'preco' => trim($_POST['preco']),
                'categoria_id' => trim($_POST['categoria_id']),
                'disponivel' => isset($_POST['disponivel']) ? 1 : 0,
                'disponivel_delivery' => isset($_POST['disponivel_delivery']) ? 1 : 0,
                'destaque' => isset($_POST['destaque']) ? 1 : 0,
                'estabelecimento_id' => $_SESSION['estabelecimento_id'],
                'nome_err' => '',
                'preco_err' => '',
                'categoria_err' => ''
            ];
            
            if (empty($dados['nome'])) {
                $dados['nome_err'] = 'Por favor, insira o nome do produto.';
            }
            
            if (empty($dados['preco']) || !is_numeric($dados['preco'])) {
                $dados['preco_err'] = 'Por favor, insira um preço válido.';
            }
            
            if (empty($dados['categoria_id'])) {
                $dados['categoria_err'] = 'Por favor, selecione uma categoria.';
            }
            
            if (empty($dados['nome_err']) && empty($dados['preco_err']) && empty($dados['categoria_err'])) {
                if ($this->produtoModel->add($dados)) {
                    header('location: ' . URL_BASE . '/admin/produtos');
                    exit();
                } else {
                    die('Erro ao adicionar produto');
                }
            } else {
                $categorias = $this->categoriaModel->findByEstabelecimentoId($_SESSION['estabelecimento_id']);
                $dados['categorias'] = $categorias;
                $this->view('admin/produtos_add', $dados);
            }
        } else {
            $categorias = $this->categoriaModel->findByEstabelecimentoId($_SESSION['estabelecimento_id']);
            $dados = array_merge($this->getDadosBase(), [
                'titulo' => 'Adicionar Produto',
                'categorias' => $categorias,
                'nome' => '',
                'descricao' => '',
                'preco' => '',
                'categoria_id' => '',
                'disponivel' => 1,
                'disponivel_delivery' => 1,
                'destaque' => 0,
                'nome_err' => '',
                'preco_err' => '',
                'categoria_err' => ''
            ]);
            $this->view('admin/produtos_add', $dados);
        }
    }
    
    public function editproduto($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            
            $dados = [
                'id' => $id,
                'nome' => trim($_POST['nome']),
                'descricao' => trim($_POST['descricao']),
                'preco' => trim($_POST['preco']),
                'categoria_id' => trim($_POST['categoria_id']),
                'disponivel' => isset($_POST['disponivel']) ? 1 : 0,
                'disponivel_delivery' => isset($_POST['disponivel_delivery']) ? 1 : 0,
                'destaque' => isset($_POST['destaque']) ? 1 : 0,
                'estabelecimento_id' => $_SESSION['estabelecimento_id'],
                'nome_err' => '',
                'preco_err' => '',
                'categoria_err' => ''
            ];
            
            if (empty($dados['nome'])) {
                $dados['nome_err'] = 'Por favor, insira o nome do produto.';
            }
            
            if (empty($dados['preco']) || !is_numeric($dados['preco'])) {
                $dados['preco_err'] = 'Por favor, insira um preço válido.';
            }
            
            if (empty($dados['categoria_id'])) {
                $dados['categoria_err'] = 'Por favor, selecione uma categoria.';
            }
            
            if (empty($dados['nome_err']) && empty($dados['preco_err']) && empty($dados['categoria_err'])) {
                if ($this->produtoModel->update($dados)) {
                    header('location: ' . URL_BASE . '/admin/produtos');
                    exit();
                } else {
                    die('Erro ao atualizar produto');
                }
            } else {
                $categorias = $this->categoriaModel->findByEstabelecimentoId($_SESSION['estabelecimento_id']);
                $dados['categorias'] = $categorias;
                $this->view('admin/produtos_edit', $dados);
            }
        } else {
            $produto = $this->produtoModel->findById($id);
            $categorias = $this->categoriaModel->findByEstabelecimentoId($_SESSION['estabelecimento_id']);
            
            if (!$produto || $produto->estabelecimento_id != $_SESSION['estabelecimento_id']) {
                header('location: ' . URL_BASE . '/admin/produtos');
                exit();
            }
            
            $dados = array_merge($this->getDadosBase(), [
                'titulo' => 'Editar Produto',
                'categorias' => $categorias,
                'id' => $produto->id,
                'nome' => $produto->nome,
                'descricao' => $produto->descricao,
                'preco' => $produto->preco,
                'categoria_id' => $produto->categoria_id,
                'disponivel' => $produto->disponivel,
                'disponivel_delivery' => $produto->disponivel_delivery,
                'destaque' => $produto->destaque,
                'nome_err' => '',
                'preco_err' => '',
                'categoria_err' => ''
            ]);
            $this->view('admin/produtos_edit', $dados);
        }
    }
    
    public function deleteproduto($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $produto = $this->produtoModel->findById($id);
            
            if ($produto && $produto->estabelecimento_id == $_SESSION['estabelecimento_id']) {
                $this->produtoModel->delete($id);
            }
        }
        header('location: ' . URL_BASE . '/admin/produtos');
        exit();
    }
    
    // Métodos para Categorias
    public function addcategoria()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            
            $dados = [
                'nome' => trim($_POST['nome']),
                'descricao' => trim($_POST['descricao']),
                'ordem' => trim($_POST['ordem']),
                'estabelecimento_id' => $_SESSION['estabelecimento_id'],
                'nome_err' => ''
            ];
            
            if (empty($dados['nome'])) {
                $dados['nome_err'] = 'Por favor, insira o nome da categoria.';
            }
            
            if (empty($dados['nome_err'])) {
                if ($this->categoriaModel->add($dados)) {
                    header('location: ' . URL_BASE . '/admin/categorias');
                    exit();
                } else {
                    die('Erro ao adicionar categoria');
                }
            } else {
                $this->view('admin/categorias_add', $dados);
            }
        } else {
            $dados = array_merge($this->getDadosBase(), [
                'titulo' => 'Adicionar Categoria',
                'nome' => '',
                'descricao' => '',
                'ordem' => '0',
                'nome_err' => ''
            ]);
            $this->view('admin/categorias_add', $dados);
        }
    }
    
    public function editcategoria($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            
            $dados = [
                'id' => $id,
                'nome' => trim($_POST['nome']),
                'descricao' => trim($_POST['descricao']),
                'ordem' => trim($_POST['ordem']),
                'estabelecimento_id' => $_SESSION['estabelecimento_id'],
                'nome_err' => ''
            ];
            
            if (empty($dados['nome'])) {
                $dados['nome_err'] = 'Por favor, insira o nome da categoria.';
            }
            
            if (empty($dados['nome_err'])) {
                if ($this->categoriaModel->update($dados)) {
                    header('location: ' . URL_BASE . '/admin/categorias');
                    exit();
                } else {
                    die('Erro ao atualizar categoria');
                }
            } else {
                $this->view('admin/categorias_edit', $dados);
            }
        } else {
            $categoria = $this->categoriaModel->findById($id);
            
            if (!$categoria || $categoria->estabelecimento_id != $_SESSION['estabelecimento_id']) {
                header('location: ' . URL_BASE . '/admin/categorias');
                exit();
            }
            
            $dados = array_merge($this->getDadosBase(), [
                'titulo' => 'Editar Categoria',
                'id' => $categoria->id,
                'nome' => $categoria->nome,
                'descricao' => $categoria->descricao,
                'ordem' => $categoria->ordem,
                'nome_err' => ''
            ]);
            $this->view('admin/categorias_edit', $dados);
        }
    }
    
    public function deletecategoria($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $categoria = $this->categoriaModel->findById($id);
            
            if ($categoria && $categoria->estabelecimento_id == $_SESSION['estabelecimento_id']) {
                $this->categoriaModel->delete($id);
            }
        }
        header('location: ' . URL_BASE . '/admin/categorias');
        exit();
    }
    
    // Métodos para Mesas
    public function addmesa()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            
            $dados = [
                'numero' => trim($_POST['numero'] ?? ''),
                'capacidade' => trim($_POST['capacidade'] ?? '4'), // Capacidade padrão
                'estabelecimento_id' => $_SESSION['estabelecimento_id'],
                'numero_err' => '',
                'capacidade_err' => ''
            ];
            
            if (empty($dados['numero'])) {
                $dados['numero_err'] = 'Por favor, insira o número da mesa.';
            }
            
            if (!empty($dados['capacidade']) && !is_numeric($dados['capacidade'])) {
                $dados['capacidade_err'] = 'Por favor, insira uma capacidade válida.';
            }
            
            if (empty($dados['numero_err']) && empty($dados['capacidade_err'])) {
                $mesaId = $this->mesaModel->add($dados);
                if ($mesaId) {
                    // Gerar QR Code
                    $qrCodePath = $this->gerarQrCode($mesaId, $dados['numero']);
                    if ($qrCodePath) {
                        $this->mesaModel->updateQrCode($mesaId, $qrCodePath);
                    }
                    header('location: ' . URL_BASE . '/admin/mesas');
                    exit();
                } else {
                    die('Erro ao adicionar mesa');
                }
            } else {
                $this->view('admin/mesas_add', $dados);
            }
        } else {
            $dados = array_merge($this->getDadosBase(), [
                'titulo' => 'Adicionar Mesa',
                'numero' => '',
                'capacidade' => '4',
                'numero_err' => '',
                'capacidade_err' => ''
            ]);
            $this->view('admin/mesas_add', $dados);
        }
    }
    
    public function editmesa($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            
            $dados = [
                'id' => $id,
                'numero' => trim($_POST['numero'] ?? ''),
                'capacidade' => trim($_POST['capacidade'] ?? '4'), // Capacidade padrão
                'estabelecimento_id' => $_SESSION['estabelecimento_id'],
                'numero_err' => '',
                'capacidade_err' => ''
            ];
            
            if (empty($dados['numero'])) {
                $dados['numero_err'] = 'Por favor, insira o número da mesa.';
            }
            
            if (!empty($dados['capacidade']) && !is_numeric($dados['capacidade'])) {
                $dados['capacidade_err'] = 'Por favor, insira uma capacidade válida.';
            }
            
            if (empty($dados['numero_err']) && empty($dados['capacidade_err'])) {
                if ($this->mesaModel->update($dados)) {
                    header('location: ' . URL_BASE . '/admin/mesas');
                    exit();
                } else {
                    die('Erro ao atualizar mesa');
                }
            } else {
                $this->view('admin/mesas_edit', $dados);
            }
        } else {
            $mesa = $this->mesaModel->findById($id);
            
            if (!$mesa || $mesa->estabelecimento_id != $_SESSION['estabelecimento_id']) {
                header('location: ' . URL_BASE . '/admin/mesas');
                exit();
            }
            
            $dados = array_merge($this->getDadosBase(), [
                'titulo' => 'Editar Mesa',
                'id' => $mesa->id,
                'numero' => $mesa->numero,
                'capacidade' => $mesa->capacidade ?? '4',
                'numero_err' => '',
                'capacidade_err' => ''
            ]);
            $this->view('admin/mesas_edit', $dados);
        }
    }
    
    public function deletemesa($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $mesa = $this->mesaModel->findById($id);
            
            if ($mesa && $mesa->estabelecimento_id == $_SESSION['estabelecimento_id']) {
                $this->mesaModel->delete($id);
            }
        }
        header('location: ' . URL_BASE . '/admin/mesas');
        exit();
    }
    
    // Método para gerar QR Code
    private function gerarQrCode($mesaId, $numeroMesa)
    {
        // Incluir a biblioteca de QR Code
        require_once '../app/helpers/qrcode_helper.php';
        
        // URL do cardápio para a mesa específica
        $url = URL_BASE . '/cardapio?mesa=' . $mesaId;
        
        // Criar diretório se não existir
        $qrDir = '../public/uploads/qrcodes/';
        if (!file_exists($qrDir)) {
            mkdir($qrDir, 0755, true);
        }
        
        // Nome do arquivo
        $fileName = 'mesa_' . $mesaId . '_' . $numeroMesa . '.png';
        $filePath = $qrDir . $fileName;
        
        try {
            // Gerar QR Code
            QRcode::png($url, $filePath, QR_ECLEVEL_L, 8, 2);
            
            // Verificar se o arquivo foi criado
            if (file_exists($filePath)) {
                return $fileName; // Retorna apenas o nome do arquivo
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log('Erro ao gerar QR Code: ' . $e->getMessage());
            return false;
        }
    }
    
    // Método para processar upload de logo
    private function processarUploadLogo($file)
    {
        $uploadDir = '../public/uploads/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'error' => 'Tipo de arquivo não permitido. Use JPG, PNG, GIF ou WebP.'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'Arquivo muito grande. Máximo 2MB.'];
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'logo_' . $_SESSION['estabelecimento_id'] . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return ['success' => true, 'filename' => $filename];
        } else {
            return ['success' => false, 'error' => 'Erro ao fazer upload do arquivo.'];
        }
    }
    
    // Método para carregar configurações atuais
    private function carregarConfiguracoes()
    {
        $this->db->query('SELECT * FROM estabelecimentos WHERE id = :id');
        $this->db->bind(':id', $_SESSION['estabelecimento_id']);
        $estabelecimento = $this->db->single();
        
        if ($estabelecimento) {
            return [
                'nome' => $estabelecimento->nome,
                'descricao_curta' => $estabelecimento->descricao_curta ?? '',
                'telefone' => $estabelecimento->telefone ?? '',
                'endereco' => $estabelecimento->endereco ?? '',
                'whatsapp' => $estabelecimento->whatsapp ?? '',
                'instagram' => $estabelecimento->instagram ?? '',
                'facebook' => $estabelecimento->facebook ?? '',
                'cor_primaria' => $estabelecimento->cor_primaria ?? '#dc2626',
                'cor_texto_header' => $estabelecimento->cor_texto_header ?? '#FFFFFF',
                'cor_secundaria' => $estabelecimento->cor_secundaria ?? '#1d4ed8',
                'cor_botao_pedido' => $estabelecimento->cor_botao_pedido ?? '#FFA500',
                'logo' => $estabelecimento->logo ?? ''
            ];
        }
        
        return [];
    }
    
    // Método para atualizar configurações
    private function atualizarConfiguracoes($dados)
    {
        $sql = 'UPDATE estabelecimentos SET 
                nome = :nome,
                descricao_curta = :descricao_curta,
                telefone = :telefone,
                endereco = :endereco,
                whatsapp = :whatsapp,
                instagram = :instagram,
                facebook = :facebook,
                cor_primaria = :cor_primaria,
                cor_texto_header = :cor_texto_header,
                cor_secundaria = :cor_secundaria,
                cor_botao_pedido = :cor_botao_pedido';
        
        // Adicionar logo se foi enviado
        if (!empty($dados['logo'])) {
            $sql .= ', logo = :logo';
        }
        
        $sql .= ' WHERE id = :id';
        
        $this->db->query($sql);
        $this->db->bind(':id', $dados['estabelecimento_id']);
        $this->db->bind(':nome', $dados['nome']);
        $this->db->bind(':descricao_curta', $dados['descricao_curta']);
        $this->db->bind(':telefone', $dados['telefone']);
        $this->db->bind(':endereco', $dados['endereco']);
        $this->db->bind(':whatsapp', $dados['whatsapp']);
        $this->db->bind(':instagram', $dados['instagram']);
        $this->db->bind(':facebook', $dados['facebook']);
        $this->db->bind(':cor_primaria', $dados['cor_primaria']);
        $this->db->bind(':cor_texto_header', $dados['cor_texto_header']);
        $this->db->bind(':cor_secundaria', $dados['cor_secundaria']);
        $this->db->bind(':cor_botao_pedido', $dados['cor_botao_pedido']);
        
        if (!empty($dados['logo'])) {
            $this->db->bind(':logo', $dados['logo']);
        }
        
        return $this->db->execute();
    }
}
?>