<?php require_once '../app/views/admin/partials/header.php'; ?>

<div class="p-6" id="kitchen-display">
    <?php require_once '../app/views/admin/partials/back_button.php'; ?>
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Pedidos Delivery</h2>
            <p class="text-gray-600 mt-1">Gerencie os pedidos de delivery em tempo real</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-4">
            <a href="<?= URL_BASE ?>/admin/pedidos" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                Pedidos Mesa
            </a>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Status da Conexão:</span>
                <span id="connection-status" class="h-3 w-3 rounded-full bg-green-500 animate-pulse"></span>
                <span id="connection-text" class="text-sm text-green-600 font-medium">Conectado</span>
            </div>
            <button onclick="fetchPedidos()" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                <i class="fas fa-sync-alt mr-2"></i>
                Atualizar
            </button>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Recebido -->
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recebido</h3>
                <span id="count-recebido" class="bg-gray-500 text-white text-sm font-bold py-1 px-3 rounded-full">0</span>
            </div>
            <div id="coluna-recebido" class="space-y-3 min-h-[400px]">
                <!-- Pedidos serão inseridos aqui via JavaScript -->
            </div>
        </div>

        <!-- Preparando -->
        <div class="bg-yellow-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Preparando</h3>
                <span id="count-preparando" class="bg-yellow-500 text-white text-sm font-bold py-1 px-3 rounded-full">0</span>
            </div>
            <div id="coluna-preparando" class="space-y-3 min-h-[400px]">
                <!-- Pedidos serão inseridos aqui via JavaScript -->
            </div>
        </div>

        <!-- Saiu para Entrega -->
        <div class="bg-green-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Saiu para Entrega</h3>
                <span id="count-pronto" class="bg-green-500 text-white text-sm font-bold py-1 px-3 rounded-full">0</span>
            </div>
            <div id="coluna-pronto" class="space-y-3 min-h-[400px]">
                <!-- Pedidos serão inseridos aqui via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
let knownOrderIds = new Set();

// Função para tocar som de notificação
function playNotificationSound() {
    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU5k9n1unEiBS13yO/eizEIHWq+8+OWT');
    audio.play().catch(e => console.log('Erro ao tocar som:', e));
}

// Função para renderizar um pedido
function renderizarPedido(pedido) {
    const statusColors = {
        'recebido': 'bg-gray-100 border-gray-300',
        'preparando': 'bg-yellow-100 border-yellow-300',
        'pronto': 'bg-green-100 border-green-300'
    };
    
    const statusIcons = {
        'recebido': 'fas fa-clock',
        'preparando': 'fas fa-utensils',
        'pronto': 'fas fa-motorcycle'
    };
    
    const statusButtons = {
        'recebido': '<button data-action="preparar" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Preparar</button>',
        'preparando': '<button data-action="pronto" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">Saiu para Entrega</button>',
        'pronto': '<button data-action="finalizar" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Finalizar</button>'
    };
    
    const status = pedido.status || 'recebido';
    const colorClass = statusColors[status] || statusColors['recebido'];
    const iconClass = statusIcons[status] || statusIcons['recebido'];
    const buttons = statusButtons[status] || '';
    
    return `
        <div class="bg-white rounded-lg shadow-sm border-2 ${colorClass} p-4" data-id="${pedido.id}">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center">
                    <i class="${iconClass} text-lg mr-2"></i>
                    <span class="font-semibold text-gray-800">Pedido #${pedido.id}</span>
                </div>
                <span class="text-xs text-gray-500">${new Date(pedido.created_at).toLocaleTimeString('pt-BR')}</span>
            </div>
            
            <div class="mb-3">
                <p class="text-sm text-gray-600 mb-1">
                    <i class="fas fa-motorcycle mr-1"></i>
                    <strong>Delivery:</strong> ${pedido.endereco_entrega || 'Endereço não informado'}
                </p>
                <p class="text-sm text-gray-600 mb-1">
                    <i class="fas fa-phone mr-1"></i>
                    <strong>Telefone:</strong> ${pedido.telefone_cliente || 'Não informado'}
                </p>
                <p class="text-sm text-gray-600">
                    <i class="fas fa-clock mr-1"></i>
                    <strong>Hora:</strong> ${new Date(pedido.created_at).toLocaleString('pt-BR')}
                </p>
            </div>
            
            <div class="border-t pt-2 mb-3">
                <p class="text-sm font-medium text-gray-700 mb-1">Itens do Pedido:</p>
                <div class="text-sm text-gray-600">
                    ${pedido.itens ? pedido.itens.map(item => 
                        `<div class="flex justify-between">
                            <span>${item.quantidade}x ${item.produto_nome}</span>
                            <span>R$ ${parseFloat(item.preco_total).toFixed(2)}</span>
                        </div>`
                    ).join('') : 'Itens não carregados'}
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="text-lg font-bold text-gray-800">
                    Total: R$ ${parseFloat(pedido.total).toFixed(2)}
                </div>
                <div class="flex space-x-2">
                    ${buttons}
                </div>
            </div>
        </div>
    `;
}

// Função para buscar pedidos
async function fetchPedidos() {
    const statusEl = document.getElementById('connection-status');
    
    try {
        const response = await fetch('<?= URL_BASE ?>/admin/getPedidosDeliveryAjax');
        const pedidos = await response.json();
        
        if (pedidos.erro) {
            throw new Error(pedidos.erro);
        }
        
        // Limpar colunas
        document.getElementById('coluna-recebido').innerHTML = '';
        document.getElementById('coluna-preparando').innerHTML = '';
        document.getElementById('coluna-pronto').innerHTML = '';
        
        // Resetar contadores
        const contadores = {
            recebido: 0,
            preparando: 0,
            pronto: 0
        };
        
        const colunas = {
            recebido: document.getElementById('coluna-recebido'),
            preparando: document.getElementById('coluna-preparando'),
            pronto: document.getElementById('coluna-pronto')
        };
        
        let currentOrderIds = new Set();
        
        // Ordenar pedidos por data de criação (mais antigos primeiro)
        pedidos.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
        
        pedidos.forEach(pedido => {
            currentOrderIds.add(pedido.id);
            const colunaDestino = colunas[pedido.status];
            if (colunaDestino) {
                colunaDestino.innerHTML += renderizarPedido(pedido);
                contadores[pedido.status]++;
            }
        });
        
        // Atualiza contadores
        document.getElementById('count-recebido').textContent = contadores.recebido;
        document.getElementById('count-preparando').textContent = contadores.preparando;
        document.getElementById('count-pronto').textContent = contadores.pronto;
        
        // Verifica se há novos pedidos e toca o som
        if (knownOrderIds.size > 0) {
            currentOrderIds.forEach(id => {
                if (!knownOrderIds.has(id)) {
                    playNotificationSound();
                }
            });
        }
        
        knownOrderIds = currentOrderIds;
        
    } catch (error) {
        console.error("Erro ao buscar pedidos:", error);
        statusEl.classList.remove('bg-green-500');
        statusEl.classList.add('bg-red-500');
        document.getElementById('connection-text').textContent = 'Desconectado';
    }
}

// Função para atualizar status
async function atualizarStatus(pedidoId, novoStatus) {
    try {
        const response = await fetch(`<?= URL_BASE ?>/admin/atualizarStatusPedido/${pedidoId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: novoStatus })
        });
        
        if (response.ok) {
            fetchPedidos(); // Atualiza a tela imediatamente
        } else {
            alert('Erro ao atualizar status do pedido.');
        }
    } catch (error) {
        console.error("Erro ao atualizar status:", error);
    }
}

// Adiciona event listener para os botões de ação
document.getElementById('kitchen-display').addEventListener('click', (e) => {
    const button = e.target.closest('button[data-action]');
    if (button) {
        const card = button.closest('div[data-id]');
        const pedidoId = card.dataset.id;
        const acao = button.dataset.action;
        
        let novoStatus = '';
        if(acao === 'preparar') novoStatus = 'preparando';
        if(acao === 'pronto') novoStatus = 'pronto';
        if(acao === 'finalizar') novoStatus = 'finalizado';
        
        if(novoStatus) {
            atualizarStatus(pedidoId, novoStatus);
        }
    }
});

// Inicialização
fetchPedidos(); // Carga inicial
setInterval(fetchPedidos, 10000); // Polling a cada 10 segundos
</script>

<?php require_once '../app/views/admin/partials/footer.php'; ?>


