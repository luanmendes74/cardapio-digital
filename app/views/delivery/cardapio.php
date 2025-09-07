<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio Delivery - <?= htmlspecialchars($dados['estabelecimento']->nome ?? 'Cardápio') ?></title>
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
<body class="bg-gray-50">

    <header class="bg-primaria text-header p-4 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto">
            <div class="flex items-center justify-between">
                <div class="flex items-start">
                    <?php if (!empty($dados['estabelecimento']->logo)): ?>
                        <img src="<?= URL_BASE ?>/uploads/<?= htmlspecialchars($dados['estabelecimento']->logo) ?>" alt="Logótipo" class="h-16 w-16 rounded-md object-contain bg-white mr-4 border-2 border-white flex-shrink-0">
                    <?php endif; ?>
                    <div>
                        <h1 class="text-2xl font-bold"><?= htmlspecialchars($dados['estabelecimento']->nome ?? '') ?></h1>
                        <p class="text-sm opacity-90 mt-1"><?= htmlspecialchars($dados['estabelecimento']->descricao_curta ?? '') ?></p>
                        <p class="text-xs opacity-75 mt-1"><i class="fas fa-motorcycle mr-1"></i> Delivery</p>
                    </div>
                </div>
                 <div class="text-right flex-shrink-0">
                    <button id="abrir-carrinho-btn" class="bg-botao-pedido text-white font-bold py-2 px-6 rounded-lg shadow-md hover:bg-secundaria-dark transition-all duration-200 flex items-center">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Fazer Pedido (<span id="cart-count">0</span>)
                    </button>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-white border-opacity-30 flex justify-between items-center text-sm">
                <div class="flex space-x-4">
                     <?php if (!empty($dados['estabelecimento']->telefone)): ?>
                        <span><i class="fas fa-phone-alt mr-1"></i> <?= htmlspecialchars($dados['estabelecimento']->telefone) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($dados['estabelecimento']->endereco)): ?>
                        <span><i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($dados['estabelecimento']->endereco) ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex space-x-3">
                    <?php if (!empty($dados['estabelecimento']->whatsapp)): ?>
                        <a href="https://wa.me/<?= $dados['estabelecimento']->whatsapp ?>" target="_blank" class="hover:opacity-80 transition-opacity">
                            <i class="fab fa-whatsapp text-lg"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($dados['estabelecimento']->instagram)): ?>
                        <a href="https://instagram.com/<?= $dados['estabelecimento']->instagram ?>" target="_blank" class="hover:opacity-80 transition-opacity">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($dados['estabelecimento']->facebook)): ?>
                        <a href="https://facebook.com/<?= $dados['estabelecimento']->facebook ?>" target="_blank" class="hover:opacity-80 transition-opacity">
                            <i class="fab fa-facebook text-lg"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container mx-auto px-4 py-8">
        <!-- Navegação por Categorias -->
        <nav class="mb-8">
            <div class="flex flex-wrap gap-2 justify-center">
                <button class="categoria-btn active bg-primaria text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-secundaria transition-colors" data-categoria="todos">
                    Todos
                </button>
                <?php foreach($dados['categoriasComProdutos'] as $categoria): ?>
                    <button class="categoria-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-secundaria hover:text-white transition-colors" data-categoria="<?= $categoria->id ?>">
                        <?= htmlspecialchars($categoria->nome) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </nav>

        <!-- Lista de Produtos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($dados['categoriasComProdutos'] as $categoria): ?>
                <?php foreach($categoria->produtos as $produto): ?>
                    <div class="produto-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300" data-categoria="<?= $categoria->id ?>">
                        <div class="relative">
                            <?php if (!empty($produto->imagem)): ?>
                                <img src="<?= URL_BASE ?>/uploads/<?= htmlspecialchars($produto->imagem) ?>" 
                                     alt="<?= htmlspecialchars($produto->nome) ?>" 
                                     class="w-full h-48 object-cover">
                            <?php else: ?>
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-4xl text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($produto->destaque): ?>
                                <div class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 px-2 py-1 rounded-full text-xs font-bold">
                                    Destaque
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2"><?= htmlspecialchars($produto->nome) ?></h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2"><?= htmlspecialchars($produto->descricao ?? '') ?></p>
                            
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold text-primaria">R$ <?= number_format($produto->preco, 2, ',', '.') ?></span>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-motorcycle mr-1"></i>
                                        Delivery
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <button class="flex-1 bg-gray-200 text-gray-700 px-3 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors" onclick="diminuirQuantidade(<?= $produto->id ?>)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span id="quantidade-<?= $produto->id ?>" class="px-4 py-2 font-medium">0</span>
                                <button class="flex-1 bg-secundaria text-white px-3 py-2 rounded-lg font-medium hover:bg-secundaria-dark transition-colors" onclick="aumentarQuantidade(<?= $produto->id ?>)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Modal do Carrinho -->
    <div id="carrinho-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-hidden">
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
                
                <div class="border-t p-4">
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

    <!-- Modal de Dados do Cliente -->
    <div id="dados-cliente-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="bg-primaria text-header p-4">
                    <h2 class="text-xl font-bold">Dados para Entrega</h2>
                </div>
                
                <form id="dados-cliente-form" class="p-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                        <input type="text" name="cliente_nome" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primaria focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telefone *</label>
                        <input type="tel" name="cliente_telefone" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primaria focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Endereço *</label>
                        <textarea name="cliente_endereco" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primaria focus:border-transparent"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                        <textarea name="observacoes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primaria focus:border-transparent"></textarea>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="button" id="cancelar-pedido-btn" class="flex-1 bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="flex-1 bg-botao-pedido text-white font-medium py-2 px-4 rounded-lg hover:bg-secundaria-dark transition-colors">
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
            const finalizarBtn = document.getElementById('finalizar-pedido-btn');
            
            const totalItens = Object.values(carrinho).reduce((acc, qty) => acc + qty, 0);
            cartCount.textContent = totalItens;
            
            if (totalItens === 0) {
                carrinhoItens.innerHTML = '<p class="text-gray-500 text-center py-8">Carrinho vazio</p>';
                carrinhoTotal.textContent = 'R$ 0,00';
                finalizarBtn.disabled = true;
                return;
            }
            
            let html = '';
            let total = 0;
            
            for (const [produtoId, quantidade] of Object.entries(carrinho)) {
                const produto = produtos[produtoId];
                if (produto) {
                    const subtotal = produto.preco * quantidade;
                    total += subtotal;
                    
                    html += `
                        <div class="flex items-center justify-between py-2 border-b">
                            <div class="flex-1">
                                <h4 class="font-medium">${produto.nome}</h4>
                                <p class="text-sm text-gray-600">R$ ${produto.preco.toFixed(2).replace('.', ',')}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="diminuirQuantidade(${produtoId})" class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <span class="w-8 text-center">${quantidade}</span>
                                <button onclick="aumentarQuantidade(${produtoId})" class="w-6 h-6 bg-secundaria text-white rounded-full flex items-center justify-center">
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
                document.querySelectorAll('.categoria-btn').forEach(b => b.classList.remove('active', 'bg-primaria', 'text-white'));
                document.querySelectorAll('.categoria-btn').forEach(b => b.classList.add('bg-gray-200', 'text-gray-700'));
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
                    itens.push({
                        produto_id: produtoId,
                        quantidade: quantidade
                    });
                }
            }
            
            formData.append('itens', JSON.stringify(itens));
            
            fetch('<?= URL_BASE ?>/delivery/adicionarPedido', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= URL_BASE ?>/delivery/sucesso/' + data.pedido_id;
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


