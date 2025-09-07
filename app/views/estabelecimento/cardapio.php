<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio de <?= htmlspecialchars($dados['estabelecimento']->nome ?? 'Cardápio') ?></title>
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
                 <div class="flex space-x-4">
                    <?php if (!empty($dados['estabelecimento']->whatsapp)): ?>
                        <a href="https://wa.me/<?= htmlspecialchars($dados['estabelecimento']->whatsapp) ?>" target="_blank" class="hover:opacity-80 transition-opacity"><i class="fab fa-whatsapp fa-lg"></i></a>
                    <?php endif; ?>
                     <?php if (!empty($dados['estabelecimento']->instagram)): ?>
                        <a href="https://instagram.com/<?= htmlspecialchars($dados['estabelecimento']->instagram) ?>" target="_blank" class="hover:opacity-80 transition-opacity"><i class="fab fa-instagram fa-lg"></i></a>
                    <?php endif; ?>
                     <?php if (!empty($dados['estabelecimento']->facebook)): ?>
                        <a href="https://facebook.com/<?= htmlspecialchars($dados['estabelecimento']->facebook) ?>" target="_blank" class="hover:opacity-80 transition-opacity"><i class="fab fa-facebook fa-lg"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto flex flex-col md:flex-row mt-4">
        <aside class="w-full md:w-1/4 lg:w-1/5 p-4 md:sticky md:top-36 self-start">
            <h2 class="text-lg font-bold mb-4 text-gray-700">Categorias</h2>
            <nav id="category-nav">
                <ul>
                    <?php if(!empty($dados['categoriasComProdutos'])): ?>
                        <?php foreach($dados['categoriasComProdutos'] as $categoria): ?>
                            <li class="mb-2">
                                <a href="#categoria-<?= $categoria->id ?>" class="category-link flex items-center p-3 rounded-lg text-gray-600 hover:bg-gray-200 transition-colors duration-200">
                                    <i class="fas fa-tag w-6 text-center text-gray-400"></i>
                                    <span class="ml-3 font-semibold"><?= htmlspecialchars($categoria->nome ?? '') ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </nav>
        </aside>

        <main class="w-full md:w-3/4 lg:w-4/5 p-4">
            <?php if(empty($dados['categoriasComProdutos'])): ?>
                <div class="text-center py-10 bg-white rounded-lg shadow-sm border">
                    <i class="fas fa-book-open text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700">Cardápio em Construção</h3>
                    <p class="text-gray-500 mt-2">Este estabelecimento ainda não adicionou categorias e produtos ao cardápio.</p>
                </div>
            <?php else: ?>
                <?php foreach($dados['categoriasComProdutos'] as $categoria): ?>
                    <section id="categoria-<?= $categoria->id ?>" class="mb-12">
                        <h2 class="text-3xl font-bold text-gray-800 mb-6"><?= htmlspecialchars($categoria->nome ?? '') ?></h2>
                        
                        <?php if(empty($categoria->produtos)): ?>
                            <p class="text-gray-500 italic">Nenhum produto encontrado nesta categoria.</p>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach($categoria->produtos as $produto): ?>
                                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                                        <div class="flex-1 mr-4">
                                            <div class="flex items-center mb-1">
                                                <h3 class="font-bold text-lg text-gray-900"><?= htmlspecialchars($produto->nome ?? '') ?></h3>
                                                <?php if($produto->destaque): ?>
                                                    <span class="ml-2 bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Popular</span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="text-gray-600 text-sm mb-2"><?= htmlspecialchars($produto->descricao ?? '') ?></p>
                                            <span class="font-bold text-xl text-primaria">R$ <?= number_format($produto->preco ?? 0, 2, ',', '.') ?></span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <?php if(!empty($produto->imagem)): ?>
                                                <img src="<?= URL_BASE ?>/uploads/<?= htmlspecialchars($produto->imagem) ?>" alt="<?= htmlspecialchars($produto->nome ?? '') ?>" class="w-24 h-24 md:w-32 md:h-32 rounded-md object-cover">
                                            <?php else: ?>
                                                <div class="w-24 h-24 md:w-32 md:h-32 rounded-md bg-gray-100 flex items-center justify-center">
                                                    <i class="fas fa-image text-4xl text-gray-300"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <button class="add-to-cart-btn ml-4 bg-secundaria text-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-secundaria-dark transition-transform duration-200 transform hover:scale-110"
                                            data-id="<?= $produto->id ?>"
                                            data-nome="<?= htmlspecialchars($produto->nome ?? '') ?>"
                                            data-preco="<?= $produto->preco ?? 0 ?>">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>
    </div>

    <!-- Modal do Carrinho -->
    <div id="cart-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
            <div class="p-6 border-b">
                <h3 class="text-2xl font-bold text-gray-800">Seu Pedido</h3>
            </div>
            <div id="cart-items" class="p-6 max-h-96 overflow-y-auto">
                <!-- Itens do carrinho serão inseridos aqui pelo JavaScript -->
            </div>
            <div class="p-6 border-t">
                 <textarea id="order-notes" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-4" rows="3" placeholder="Observações do pedido (ex: sem cebola, ponto da carne, etc.)"></textarea>
                <div class="flex justify-between items-center font-bold text-xl">
                    <span>Total:</span>
                    <span id="cart-total">R$ 0,00</span>
                </div>
            </div>
            <div class="p-6 bg-gray-50 rounded-b-lg flex justify-between">
                <button id="fechar-carrinho-btn" class="text-gray-600">Continuar a escolher</button>
                <button id="enviar-pedido-btn" class="bg-botao-pedido text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-secundaria-dark transition-all duration-200">
                    <i class="fas fa-check mr-2"></i> Confirmar Pedido
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modal de Sucesso -->
    <div id="success-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl text-center p-12">
            <i class="fas fa-check-circle text-6xl text-green-500 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Pedido Enviado!</h3>
            <p class="text-gray-600">O seu pedido foi enviado para a cozinha. Obrigado!</p>
             <button id="fechar-sucesso-btn" class="mt-6 bg-primaria text-white font-bold py-2 px-8 rounded-lg">Fechar</button>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cart = [];
            const mesaId = new URLSearchParams(window.location.search).get('mesa');
            const estabelecimentoId = <?= $dados['estabelecimento']->id ?>;

            const cartCountEl = document.getElementById('cart-count');
            const cartItemsEl = document.getElementById('cart-items');
            const cartTotalEl = document.getElementById('cart-total');
            const modal = document.getElementById('cart-modal');
            const successModal = document.getElementById('success-modal');

            const openModal = () => modal.classList.remove('hidden');
            const closeModal = () => modal.classList.add('hidden');
            const openSuccessModal = () => successModal.classList.remove('hidden');
            const closeSuccessModal = () => successModal.classList.add('hidden');
            
            document.getElementById('abrir-carrinho-btn').addEventListener('click', openModal);
            document.getElementById('fechar-carrinho-btn').addEventListener('click', closeModal);
            document.getElementById('fechar-sucesso-btn').addEventListener('click', closeSuccessModal);

            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const id = parseInt(button.dataset.id);
                    const nome = button.dataset.nome;
                    const preco = parseFloat(button.dataset.preco);
                    addToCart({ id, nome, preco });
                });
            });

            document.getElementById('enviar-pedido-btn').addEventListener('click', enviarPedido);

            function addToCart(product) {
                const existingProduct = cart.find(item => item.id === product.id);
                if (existingProduct) {
                    existingProduct.quantidade++;
                } else {
                    cart.push({ ...product, quantidade: 1 });
                }
                updateCart();
            }

            function updateCart() {
                cartItemsEl.innerHTML = '';
                let total = 0;
                let count = 0;

                if(cart.length === 0){
                    cartItemsEl.innerHTML = '<p class="text-gray-500">O seu carrinho está vazio.</p>';
                } else {
                    cart.forEach((item, index) => {
                        total += item.preco * item.quantidade;
                        count += item.quantidade;
                        cartItemsEl.innerHTML += `
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <p class="font-bold">${item.nome}</p>
                                    <p class="text-sm text-gray-600">R$ ${item.preco.toFixed(2).replace('.', ',')}</p>
                                </div>
                                <div class="flex items-center">
                                    <button onclick="changeQuantity(${index}, -1)" class="px-2 rounded bg-gray-200">-</button>
                                    <span class="px-3">${item.quantidade}</span>
                                    <button onclick="changeQuantity(${index}, 1)" class="px-2 rounded bg-gray-200">+</button>
                                    <button onclick="removeFromCart(${index})" class="ml-4 text-red-500"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        `;
                    });
                }
                
                cartTotalEl.innerText = `R$ ${total.toFixed(2).replace('.', ',')}`;
                cartCountEl.innerText = count;
            }
            
            window.changeQuantity = (index, delta) => {
                if (cart[index].quantidade + delta > 0) {
                    cart[index].quantidade += delta;
                } else {
                    cart.splice(index, 1);
                }
                updateCart();
            };

            window.removeFromCart = (index) => {
                cart.splice(index, 1);
                updateCart();
            };
            
            async function enviarPedido() {
                if(cart.length === 0) {
                    alert('O seu carrinho está vazio!');
                    return;
                }
                if(!mesaId) {
                    alert('Erro: ID da mesa não encontrado. Por favor, aceda através do QR Code.');
                    return;
                }

                const total = cart.reduce((sum, item) => sum + item.preco * item.quantidade, 0);
                const observacoes = document.getElementById('order-notes').value;

                const pedido = {
                    estabelecimento_id: estabelecimentoId,
                    mesa_id: mesaId,
                    total: total,
                    observacoes: observacoes,
                    items: cart
                };
                
                const btn = document.getElementById('enviar-pedido-btn');
                btn.disabled = true;
                btn.innerHTML = 'A enviar...';

                try {
                    console.log('Enviando pedido:', pedido);
                    
                    const response = await fetch('<?= URL_BASE ?>/pedido/criar', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(pedido)
                    });

                    console.log('Resposta recebida:', response.status);

                    if (response.ok) {
                        const result = await response.json();
                        console.log('Pedido criado:', result);
                        cart = [];
                        updateCart();
                        closeModal();
                        openSuccessModal();
                    } else {
                        const errorData = await response.json();
                        console.error('Erro na resposta:', errorData);
                        throw new Error(errorData.erro || 'Falha ao enviar o pedido.');
                    }
                } catch (error) {
                    console.error('Erro completo:', error);
                    alert('Erro: ' + error.message);
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check mr-2"></i> Confirmar Pedido';
                }
            }
            
            const links = document.querySelectorAll('.category-link');
            const sections = document.querySelectorAll('main section');
            if (links.length > 0) { links[0].classList.add('bg-gray-200', 'text-primaria'); }
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        links.forEach(link => {
                            link.classList.remove('bg-gray-200', 'text-primaria');
                            if (link.getAttribute('href') === `#${entry.target.id}`) {
                                link.classList.add('bg-gray-200', 'text-primaria');
                            }
                        });
                    }
                });
            }, { rootMargin: '-50% 0px -50% 0px', threshold: 0 });
            sections.forEach(section => { observer.observe(section); });
        });
    </script>
</body>
</html>

