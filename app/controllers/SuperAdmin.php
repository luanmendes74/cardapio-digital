<?php
class SuperAdmin extends Controller
{
    private $superAdminModel;
    private $estabelecimentoModel;
    private $usuarioModel;
    private $planoModel;

    public function __construct()
    {
        if (!isSuperAdminLoggedIn() && (strtolower($_GET['url'] ?? '') !== 'superadmin/login')) {
            header('location: ' . URL_BASE . '/SuperAdmin/login');
            exit();
        }

        $this->superAdminModel = $this->model('SuperAdminModel');
        $this->estabelecimentoModel = $this->model('EstabelecimentoModel');
        $this->usuarioModel = $this->model('UsuarioModel'); 
        $this->planoModel = $this->model('PlanoModel');
    }

    public function index()
    {
        $estabelecimentos = $this->estabelecimentoModel->findAll();
        $dados = [ 'titulo' => 'Gestão de Estabelecimentos', 'estabelecimentos' => $estabelecimentos ];
        $this->view('superadmin/index', $dados);
    }
    
    // --- CRUD DE ESTABELECIMENTOS ---
    public function addEstabelecimento()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $dados = ['nome' => trim($_POST['nome']), 'subdominio' => trim($_POST['subdominio']), 'admin_nome' => trim($_POST['admin_nome']), 'admin_email' => trim($_POST['admin_email']), 'admin_senha' => trim($_POST['admin_senha']), 'nome_err' => '', 'subdominio_err' => '', 'admin_nome_err' => '', 'admin_email_err' => '', 'admin_senha_err' => '' ];

            if (empty($dados['nome'])) $dados['nome_err'] = 'O nome do estabelecimento é obrigatório.';
            if (empty($dados['subdominio'])) $dados['subdominio_err'] = 'O subdomínio é obrigatório.';
            if (empty($dados['admin_nome'])) $dados['admin_nome_err'] = 'O nome do admin é obrigatório.';
            if (empty($dados['admin_email'])) {$dados['admin_email_err'] = 'O email do admin é obrigatório.';} else {if ($this->estabelecimentoModel->findByEmail($dados['admin_email'])) {$dados['admin_email_err'] = 'Este email já está a ser utilizado por outro estabelecimento.';}}
            if (empty($dados['admin_senha'])) $dados['admin_senha_err'] = 'A senha do admin é obrigatória.';

            if (empty($dados['nome_err']) && empty($dados['subdominio_err']) && empty($dados['admin_nome_err']) && empty($dados['admin_email_err']) && empty($dados['admin_senha_err'])) {
                $novoEstabelecimentoId = $this->estabelecimentoModel->add($dados);
                if ($novoEstabelecimentoId) {
                    $dados['estabelecimento_id'] = $novoEstabelecimentoId;
                    if ($this->usuarioModel->add($dados)) {
                        flash('estabelecimento_mensagem', 'Estabelecimento e utilizador admin criados com sucesso!');
                        header('location: ' . URL_BASE . '/SuperAdmin'); exit();
                    } else { die('Erro ao criar o utilizador admin.'); }
                } else { die('Erro ao criar o estabelecimento.'); }
            } else { $this->view('superadmin/estabelecimentos_add', $dados); }
        } else { $dados = ['titulo' => 'Adicionar Estabelecimento']; $this->view('superadmin/estabelecimentos_add', $dados); }
    }

    public function editEstabelecimento($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            $dados = [ 'id' => $id, 'nome' => trim($_POST['nome']), 'subdominio' => trim($_POST['subdominio']), 'nome_err' => '', 'subdominio_err' => '' ];

            if (empty($dados['nome'])) $dados['nome_err'] = 'O nome do estabelecimento é obrigatório.';
            if (empty($dados['subdominio'])) $dados['subdominio_err'] = 'O subdomínio é obrigatório.';

            if (empty($dados['nome_err']) && empty($dados['subdominio_err'])) {
                if ($this->estabelecimentoModel->update($dados)) {
                    flash('estabelecimento_mensagem', 'Estabelecimento atualizado com sucesso!');
                    header('location: ' . URL_BASE . '/SuperAdmin'); exit();
                } else { die('Erro ao atualizar o estabelecimento.'); }
            } else { $this->view('superadmin/estabelecimentos_edit', $dados); }
        } else {
            $est = $this->estabelecimentoModel->findById($id);
            if(!$est){ header('location: ' . URL_BASE . '/SuperAdmin'); exit(); }
            $dados = [ 'titulo' => 'Editar Estabelecimento', 'id' => $est->id, 'nome' => $est->nome, 'subdominio' => $est->subdominio ];
            $this->view('superadmin/estabelecimentos_edit', $dados);
        }
    }

    public function deleteEstabelecimento($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->usuarioModel->deleteByEstabelecimentoId($id)) {
                if ($this->estabelecimentoModel->delete($id)) {
                    flash('estabelecimento_mensagem', 'Estabelecimento apagado com sucesso.');
                    header('location: ' . URL_BASE . '/SuperAdmin'); exit();
                }
            }
            flash('estabelecimento_mensagem', 'Erro ao apagar o estabelecimento.', 'bg-red-100 border-red-400 text-red-700');
            header('location: ' . URL_BASE . '/SuperAdmin'); exit();
        }
    }

    // --- GESTÃO DE PLANOS ---
    public function planos(){$planos=$this->planoModel->findAll();$dados=['titulo'=>'Planos de Assinatura','planos'=>$planos];$this->view('superadmin/planos',$dados);}
    public function addPlano(){if($_SERVER['REQUEST_METHOD']=='POST'){$_POST=filter_input_array(INPUT_POST,FILTER_DEFAULT);$dados=['nome'=>trim($_POST['nome']),'preco_mensal'=>trim($_POST['preco_mensal']),'limite_produtos'=>trim($_POST['limite_produtos']),'limite_mesas'=>trim($_POST['limite_mesas']),'recursos'=>trim($_POST['recursos']),'nome_err'=>'','preco_mensal_err'=>''];if(empty($dados['nome']))$dados['nome_err']='O nome do plano é obrigatório.';if(empty($dados['preco_mensal']))$dados['preco_mensal_err']='O preço é obrigatório.';if(empty($dados['nome_err'])&&empty($dados['preco_mensal_err'])){if($this->planoModel->add($dados)){flash('plano_mensagem','Plano criado com sucesso!');header('location: '.URL_BASE.'/SuperAdmin/planos');exit();}else{die('Erro ao criar o plano.');}}else{$this->view('superadmin/planos_add',$dados);}}else{$dados=['titulo'=>'Adicionar Plano'];$this->view('superadmin/planos_add',$dados);}}
    public function editPlano($id){if($_SERVER['REQUEST_METHOD']=='POST'){$_POST=filter_input_array(INPUT_POST,FILTER_DEFAULT);$dados=['id'=>$id,'nome'=>trim($_POST['nome']),'preco_mensal'=>trim($_POST['preco_mensal']),'limite_produtos'=>trim($_POST['limite_produtos']),'limite_mesas'=>trim($_POST['limite_mesas']),'recursos'=>trim($_POST['recursos']),'nome_err'=>'','preco_mensal_err'=>''];if(empty($dados['nome']))$dados['nome_err']='O nome do plano é obrigatório.';if(empty($dados['preco_mensal']))$dados['preco_mensal_err']='O preço é obrigatório.';if(empty($dados['nome_err'])&&empty($dados['preco_mensal_err'])){if($this->planoModel->update($dados)){flash('plano_mensagem','Plano atualizado com sucesso!');header('location: '.URL_BASE.'/SuperAdmin/planos');exit();}else{die('Erro ao atualizar o plano.');}}else{$this->view('superadmin/planos_edit',$dados);}}else{$plano=$this->planoModel->findById($id);if(!$plano){header('location: '.URL_BASE.'/SuperAdmin/planos');exit();}$dados=['titulo'=>'Editar Plano','id'=>$plano->id,'nome'=>$plano->nome,'preco_mensal'=>$plano->preco_mensal,'limite_produtos'=>$plano->limite_produtos,'limite_mesas'=>$plano->limite_mesas,'recursos'=>$plano->recursos];$this->view('superadmin/planos_edit',$dados);}}
    public function deletePlano($id){if($_SERVER['REQUEST_METHOD']=='POST'){if($this->planoModel->delete($id)){flash('plano_mensagem','Plano apagado com sucesso.');}else{flash('plano_mensagem','Erro ao apagar o plano. Verifique se existem estabelecimentos associados a este plano.','bg-red-100 border-red-400 text-red-700');}header('location: '.URL_BASE.'/SuperAdmin/planos');exit();}}
    
    // --- Outros Métodos ---
    public function ativar($id) { if ($_SERVER['REQUEST_METHOD'] == 'POST') { if ($this->estabelecimentoModel->changeStatus($id, 1)) { flash('estabelecimento_mensagem', 'Estabelecimento ativado com sucesso!'); } else { flash('estabelecimento_mensagem', 'Ocorreu um erro.', 'bg-red-100 border border-red-400 text-red-700'); } } header('location: ' . URL_BASE . '/SuperAdmin'); exit(); }
    public function desativar($id) { if ($_SERVER['REQUEST_METHOD'] == 'POST') { if ($this->estabelecimentoModel->changeStatus($id, 0)) { flash('estabelecimento_mensagem', 'Estabelecimento desativado com sucesso.', 'bg-yellow-100 border border-yellow-400 text-yellow-700'); } else { flash('estabelecimento_mensagem', 'Ocorreu um erro.', 'bg-red-100 border border-red-400 text-red-700'); } } header('location: ' . URL_BASE . '/SuperAdmin'); exit(); }
    public function configuracoes(){$dados = ['titulo' => 'Configurações do Sistema'];$this->view('superadmin/partials/header', $dados);echo '<div class="container mx-auto"><h1>Configurações do Sistema</h1><p>Esta funcionalidade será implementada em breve.</p></div>';$this->view('superadmin/partials/footer');}
    public function login(){if(isSuperAdminLoggedIn()){header('location: '.URL_BASE.'/SuperAdmin');exit();}if($_SERVER['REQUEST_METHOD']=='POST'){$_POST=filter_input_array(INPUT_POST,FILTER_DEFAULT);$dados=['email'=>trim($_POST['email']),'senha'=>trim($_POST['senha']),'email_err'=>'','senha_err'=>''];if(empty($dados['email']))$dados['email_err']='Por favor, insira o email.';if(empty($dados['senha']))$dados['senha_err']='Por favor, insira a senha.';if(empty($dados['email_err'])&&empty($dados['senha_err'])){$loggedInAdmin=$this->superAdminModel->login($dados['email'],$dados['senha']);if($loggedInAdmin){$this->createSuperAdminSession($loggedInAdmin);}else{$dados['senha_err']='Email ou senha incorretos.';$this->view('superadmin/login',$dados);}}else{$this->view('superadmin/login',$dados);}}else{$dados=['email'=>'','senha'=>'','email_err'=>'','senha_err'=>''];$this->view('superadmin/login',$dados);}}
    public function createSuperAdminSession($admin){$_SESSION['super_admin_id']=$admin->id;$_SESSION['super_admin_nome']=$admin->nome;header('location: '.URL_BASE.'/SuperAdmin');exit();}
    public function logout(){unset($_SESSION['super_admin_id']);unset($_SESSION['super_admin_nome']);session_destroy();header('location: '.URL_BASE.'/SuperAdmin/login');exit();}
}

