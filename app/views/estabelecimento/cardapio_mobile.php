<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio - <?= htmlspecialchars($dados['estabelecimento']->nome ?? 'Cardápio') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --cor-primaria: <?= htmlspecialchars($dados['estabelecimento']->cor_primaria ?? '#dc2626') ?>;
            --cor-secundaria: <?= htmlspecialchars($dados['estabelecimento']->cor_secundaria ?? '#1d4ed8') ?>;
            --cor-texto-header: <?= htmlspecialchars($dados['estabelecimento']->cor_texto_header ?? '#FFFFFF') ?>;
            --cor-botao-pedido: <?= htmlspecialchars($dados['estabelecimento']->cor_botao_pedido ?? '#FFA500') ?>;
        }
        .bg-primaria { background-color: var(--cor-primaria); }
        .text-primaria { color: var(--cor-primaria); }
        .bg-secundaria { background-color: var(--cor-secundaria); }
        .bg-botao-pedido { background-color: var(--cor-botao-pedido); }
        .text-header { color: var(--cor-texto-header); }
        .hover\:bg-secundaria-dark:hover { filter: brightness(0.9); }
        .border-primaria { border-color: var(--cor-primaria); }
        .text-secundaria { color: var(--cor-secundaria); }
        
        /* Mobile optimizations */
        .mobile-header { height: auto; min-height: 80px; }
        .mobile-product-card { min-height: 200px; }
        .mobile-cart-btn { 
            position: fixed; 
            bottom: 20px; 
            right: 20px; 
            z-index: 40;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .mobile-cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-gray-50 pb-20">

    <!-- Header Mobile Otimizado -->
    <header class="bg-primaria text-header shadow-lg sticky top-0 z-50 mobile-header">
        <div class="px-3 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center flex-1 min-w-0">
                    <?php if (!empty($dados['estabelecimento']->logo)): ?>
                        <img src="<?= URL_BASE ?>/uploads/<?= htmlspecialchars($dados['estabelecimento']->logo) ?>" alt="Logótipo" class="h-12 w-12 rounded-md object-contain bg-white border-2 border-white flex-shrink-0 mr-3">
                    <?php endif; ?>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-lg font-bold truncate"><?= htmlspecialchars($dados['estabelecimento']->nome ?? '') ?></h1>
                        <p class="text-xs opacity-90 truncate"><?= htmlspecialchars($dados['estabelecimento']->descricao_curta ?? '') ?></p>
                    </div>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <button id="abrir-carrinho-btn" class="bg-botao-pedido text-white font-bold py-2 px-3 rounded-lg shadow-md hover:bg-secundaria-dark transition-all duration-200 flex items-center text-sm">
                        <i class="fas fa-shopping-cart mr-1"></i>
                        <span class="hidden sm:inline">Fazer Pedido</span>
                        <span class="sm:hidden">Pedido</span>
                        (<span id="cart-count">0</span>)
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Informações de Contato - Mobile -->
        <div class="px-3 py-2 bg-black bg-opacity-10 border-t border-white border-opacity-20">
            <div class="flex flex-wrap items-center justify-between text-xs">
                <div class="flex flex-wrap gap-3 mb-1">
                    <?php if (!empty($dados['estabelecimento']->telefone)): ?>
                        <span class="flex items-center"><i class="fas fa-phone-alt mr-1"></i> <?= htmlspecialchars($dados['estabelecimento']->telefone) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($dados['estabelecimento']->endereco)): ?>
                        <span class="flex items-center"><i class="fas fa-map-marker-alt mr-1"></i> <span class="truncate max-w-32"><?= htmlspecialchars($dados['estabelecimento']->endereco) ?></span></span>
                    <?php endif; ?>
                </div>
                <div class="flex space-x-3">
                    <?php if (!empty($dados['estabelecimento']->whatsapp)): ?>
                        <a href="https://wa.me/<?= htmlspecialchars($dados['estabelecimento']->whatsapp) ?>" target="_blank" class="hover:opacity-80 transition-opacity">
                            <i class="fab fa-whatsapp text-base"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($dados['estabelecimento']->instagram)): ?>
                        <a href="https://instagram.com/<?= htmlspecialchars($dados['estabelecimento']->instagram) ?>" target="_blank" class="hover:opacity-80 transition-opacity">
                            <i class="fab fa-instagram text-base"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($dados['estabelecimento']->facebook)): ?>
                        <a href="https://facebook.com/<?= htmlspecialchars($dados['estabelecimento']->facebook) ?>" target="_blank" class="hover:opacity-80 transition-opacity">
                            <i class="fab fa-facebook text-base"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal Mobile -->
    <main class="px-3 py-4">
        <!-- Navegação por Categorias - Mobile -->
        <nav class="mb-6">
            <div class="flex overflow-x-auto gap-2 pb-2 -mx-3 px-3">
                <button class="categoria-btn active bg-primaria text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-secundaria transition-colors whitespace-nowrap" data-categoria="todos">
                    Todos
                </button>
                <?php foreach($dados['categoriasComProdutos'] as $categoria): ?>
                    <button class="categoria-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-secundaria hover:text-white transition-colors whitespace-nowrap" data-categoria="<?= $categoria->id ?>">
                        <?= htmlspecialchars($categoria->nome) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </nav>

        <!-- Lista de Produtos - Mobile Grid -->
        <div class="grid grid-cols-1 gap-4">
            <?php foreach($dados['categoriasComProdutos'] as $categoria): ?>
                <?php foreach($categoria->produtos as $produto): ?>
                    <div class="produto-card bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300 mobile-product-card" data-categoria="<?= $categoria->id ?>">
                        <div class="flex">
                            <!-- Imagem do Produto -->
                            <div class="w-24 h-24 flex-shrink-0">
                                <?php if (!empty($produto->imagem)): ?>
                                    <img src="<?= URL_BASE ?>/uploads/<?= htmlspecialchars($produto->imagem) ?>" 
                                         alt="<?= htmlspecialchars($produto->nome) ?>" 
                                         class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-2xl text-gray-400"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Conteúdo do Produto -->
                            <div class="flex-1 p-3 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between mb-1">
                                        <h3 class="text-base font-bold text-gray-900 line-clamp-1"><?= htmlspecialchars($produto->nome) ?></h3>
                                        <?php if ($produto->destaque): ?>
                                            <span class="bg-yellow-400 text-yellow-900 px-2 py-1 rounded-full text-xs font-bold ml-2 flex-shrink-0">
                                                Destaque
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-gray-600 text-sm line-clamp-2 mb-2"><?= htmlspecialchars($produto->descricao ?? '') ?></p>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-primaria">R$ <?= number_format($produto->preco, 2, ',', '.') ?></span>
                                    
                                    <!-- Controles de Quantidade -->
                                    <div class="flex items-center space-x-2">
                                        <button class="w-8 h-8 bg-gray-200 text-gray-700 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors" onclick="diminuirQuantidade(<?= $produto->id ?>)">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <span id="quantidade-<?= $produto->id ?>" class="w-8 text-center font-medium">0</span>
                                        <button class="w-8 h-8 bg-secundaria text-white rounded-full flex items-center justify-center hover:bg-secundaria-dark transition-colors" onclick="aumentarQuantidade(<?= $produto->id ?>)">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Botão Flutuante do Carrinho - Mobile -->
    <button id="mobile-cart-btn" class="mobile-cart-btn bg-botao-pedido text-white font-bold py-4 px-6 rounded-full shadow-lg hover:bg-secundaria-dark transition-all duration-200 flex items-center hidden">
        <i class="fas fa-shopping-cart mr-2"></i>
        <span>Ver Pedido</span>
        <span id="mobile-cart-count" class="mobile-cart-count">0</span>
    </button>

    <!-- Modal do Carrinho - Mobile -->
    <div id="carrinho-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-end justify-center min-h-screen p-0">
            <div class="bg-white rounded-t-lg shadow-xl w-full max-h-[85vh] overflow-hidden">
                <div class="bg-primaria text-header p-4 flex items-center justify-between">
                    <h2 class="text-xl font-bold">Seu Pedido</h2>
                    <button id="fechar-carrinho-btn" class="text-header hover:opacity-80">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="p-4 max-h-96 overflow-y-auto">
                    <div id="carrinho-itens">
                        <p class="text-gray-500 text-center py-8">Carrinho vazio</p>
                    </div>
                </div>
                
                <div class="border-t p-4 bg-gray-50">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-bold">Total:</span>
                        <span id="carrinho-total" class="text-xl font-bold text-primaria">R$ 0,00</span>
                    </div>
                    
                    <button id="finalizar-pedido-btn" class="w-full bg-botao-pedido text-white font-bold py-3 px-4 rounded-lg hover:bg-secundaria-dark transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Finalizar Pedido
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Dados do Cliente - Mobile -->
    <div id="dados-cliente-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-end justify-center min-h-screen p-0">
            <div class="bg-white rounded-t-lg shadow-xl w-full max-h-[90vh] overflow-hidden">
                <div class="bg-primaria text-header p-4">
                    <h2 class="text-xl font-bold">Dados do Pedido</h2>
                </div>
                
                <form id="dados-cliente-form" class="p-4 space-y-4 max-h-96 overflow-y-auto">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                        <input type="text" name="cliente_nome" required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primaria focus:border-transparent text-base">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telefone *</label>
                        <input type="tel" name="cliente_telefone" required class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primaria focus:border-transparent text-base">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                        <textarea name="observacoes" rows="3" class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primaria focus:border-transparent text-base"></textarea>
                    </div>
                    
                    <div class="flex space-x-3 pt-4">
                        <button type="button" id="cancelar-pedido-btn" class="flex-1 bg-gray-300 text-gray-700 font-medium py-3 px-4 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="flex-1 bg-botao-pedido text-white font-medium py-3 px-4 rounded-lg hover:bg-secundaria-dark transition-colors">
                            Enviar Pedido
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let carrinho = {};
        let produtos = <?= json_encode(array_reduce($dados['categoriasComProdutos'], function($acc, $cat) {
            foreach($cat->produtos as $prod) {
                $acc[$prod->id] = $prod;
            }
            return $acc;
        }, [])) ?>;

        // Funções do carrinho
        function aumentarQuantidade(produtoId) {
            if (!carrinho[produtoId]) {
                carrinho[produtoId] = 0;
            }
            carrinho[produtoId]++;
            atualizarQuantidade(produtoId);
            atualizarCarrinho();
        }

        function diminuirQuantidade(produtoId) {
            if (carrinho[produtoId] && carrinho[produtoId] > 0) {
                carrinho[produtoId]--;
                if (carrinho[produtoId] === 0) {
                    delete carrinho[produtoId];
                }
                atualizarQuantidade(produtoId);
                atualizarCarrinho();
            }
        }

        function atualizarQuantidade(produtoId) {
            const elemento = document.getElementById(`quantidade-${produtoId}`);
            if (elemento) {
                elemento.textContent = carrinho[produtoId] || 0;
            }
        }

        function atualizarCarrinho() {
            const carrinhoItens = document.getElementById('carrinho-itens');
            const carrinhoTotal = document.getElementById('carrinho-total');
            const cartCount = document.getElementById('cart-count');
            const mobileCartCount = document.getElementById('mobile-cart-count');
            const mobileCartBtn = document.getElementById('mobile-cart-btn');
            const finalizarBtn = document.getElementById('finalizar-pedido-btn');
            
            const totalItens = Object.values(carrinho).reduce((acc, qty) => acc + qty, 0);
            cartCount.textContent = totalItens;
            mobileCartCount.textContent = totalItens;
            
            if (totalItens === 0) {
                carrinhoItens.innerHTML = '<p class="text-gray-500 text-center py-8">Carrinho vazio</p>';
                carrinhoTotal.textContent = 'R$ 0,00';
                finalizarBtn.disabled = true;
                mobileCartBtn.classList.add('hidden');
                return;
            }
            
            mobileCartBtn.classList.remove('hidden');
            
            let html = '';
            let total = 0;
            
            for (const [produtoId, quantidade] of Object.entries(carrinho)) {
                const produto = produtos[produtoId];
                if (produto) {
                    const subtotal = produto.preco * quantidade;
                    total += subtotal;
                    
                    html += `
                        <div class="flex items-center justify-between py-3 border-b">
                            <div class="flex-1">
                                <h4 class="font-medium text-sm">${produto.nome}</h4>
                                <p class="text-sm text-gray-600">R$ ${produto.preco.toFixed(2).replace('.', ',')}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="diminuirQuantidade(${produtoId})" class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <span class="w-8 text-center text-sm">${quantidade}</span>
                                <button onclick="aumentarQuantidade(${produtoId})" class="w-8 h-8 bg-secundaria text-white rounded-full flex items-center justify-center">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                        </div>
                    `;
                }
            }
            
            carrinhoItens.innerHTML = html;
            carrinhoTotal.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
            finalizarBtn.disabled = false;
        }

        // Filtro por categoria
        document.querySelectorAll('.categoria-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const categoria = this.dataset.categoria;
                
                // Atualizar botões ativos
                document.querySelectorAll('.categoria-btn').forEach(b => {
                    b.classList.remove('active', 'bg-primaria', 'text-white');
                    b.classList.add('bg-gray-200', 'text-gray-700');
                });
                this.classList.add('active', 'bg-primaria', 'text-white');
                this.classList.remove('bg-gray-200', 'text-gray-700');
                
                // Filtrar produtos
                document.querySelectorAll('.produto-card').forEach(card => {
                    if (categoria === 'todos' || card.dataset.categoria === categoria) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Event listeners
        document.getElementById('abrir-carrinho-btn').addEventListener('click', function() {
            document.getElementById('carrinho-modal').classList.remove('hidden');
        });

        document.getElementById('mobile-cart-btn').addEventListener('click', function() {
            document.getElementById('carrinho-modal').classList.remove('hidden');
        });

        document.getElementById('fechar-carrinho-btn').addEventListener('click', function() {
            document.getElementById('carrinho-modal').classList.add('hidden');
        });

        document.getElementById('finalizar-pedido-btn').addEventListener('click', function() {
            document.getElementById('carrinho-modal').classList.add('hidden');
            document.getElementById('dados-cliente-modal').classList.remove('hidden');
        });

        document.getElementById('cancelar-pedido-btn').addEventListener('click', function() {
            document.getElementById('dados-cliente-modal').classList.add('hidden');
        });

        document.getElementById('dados-cliente-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const itens = [];
            
            for (const [produtoId, quantidade] of Object.entries(carrinho)) {
                if (quantidade > 0) {
                    const produto = produtos[produtoId];
                    itens.push({
                        produto_id: produtoId,
                        quantidade: quantidade,
                        preco: produto.preco,
                        observacoes: ''
                    });
                }
            }
            
            formData.append('itens', JSON.stringify(itens));
            formData.append('estabelecimento_id', '<?= $dados['estabelecimento']->id ?>');
            formData.append('mesa_id', '1'); // Mesa padrão para pedidos gerais
            
            fetch('<?= URL_BASE ?>/pedido/adicionar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Pedido realizado com sucesso!');
                    // Limpar carrinho
                    carrinho = {};
                    atualizarCarrinho();
                    document.getElementById('dados-cliente-modal').classList.add('hidden');
                } else {
                    alert('Erro ao processar pedido: ' + (data.message || 'Tente novamente'));
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao processar pedido. Tente novamente.');
            });
        });

        // Inicializar
        atualizarCarrinho();
    </script>
</body>
</html>
