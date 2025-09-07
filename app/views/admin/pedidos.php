<?php require_once '../app/views/admin/partials/header.php'; ?>

<div class="p-6" id="kitchen-display">
    <?php require_once '../app/views/admin/partials/back_button.php'; ?>
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Pedidos de Mesa</h2>
            <p class="text-gray-600 mt-1">Gerencie os pedidos de mesa em tempo real</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-4">
            <a href="<?= URL_BASE ?>/admin/pedidosDelivery" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                <i class="fas fa-motorcycle mr-2"></i>
                Pedidos Delivery
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Coluna Recebidos -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-red-500 mr-3"></div>
                    <h3 class="text-lg font-semibold text-gray-800">Recebidos</h3>
                    <span id="count-recebido" class="ml-auto bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">0</span>
                </div>
            </div>
            <div id="coluna-recebido" class="p-6 min-h-[60vh] space-y-4">
                <!-- Pedidos serão inseridos aqui -->
            </div>
        </div>

        <!-- Coluna Em Preparação -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-yellow-50">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-yellow-500 mr-3"></div>
                    <h3 class="text-lg font-semibold text-gray-800">Em Preparação</h3>
                    <span id="count-preparando" class="ml-auto bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">0</span>
                </div>
            </div>
            <div id="coluna-preparando" class="p-6 min-h-[60vh] space-y-4">
                <!-- Pedidos serão inseridos aqui -->
            </div>
        </div>

        <!-- Coluna Pronto para Servir -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-green-500 mr-3"></div>
                    <h3 class="text-lg font-semibold text-gray-800">Pronto para Servir</h3>
                    <span id="count-pronto" class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">0</span>
                </div>
            </div>
            <div id="coluna-pronto" class="p-6 min-h-[60vh] space-y-4">
                <!-- Pedidos serão inseridos aqui -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const colunas = {
        recebido: document.getElementById('coluna-recebido'),
        preparando: document.getElementById('coluna-preparando'),
        pronto: document.getElementById('coluna-pronto')
    };
    const statusEl = document.getElementById('connection-status');
    let audioContext;
    let knownOrderIds = new Set();

    function playNotificationSound() {
        if (!audioContext) {
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
        }
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(440, audioContext.currentTime);
        gainNode.gain.setValueAtTime(0.5, audioContext.currentTime);
        oscillator.start();
        oscillator.stop(audioContext.currentTime + 0.2);
    }

    function renderizarPedido(pedido) {
        let itensHtml = '';
        pedido.itens.forEach(item => {
            itensHtml += `
                <li class="flex justify-between text-sm py-1">
                    <span class="text-gray-700">${item.quantidade}x ${item.nome_produto}</span>
                    <span class="font-medium text-gray-900">R$ ${parseFloat(item.preco_total).toFixed(2).replace('.', ',')}</span>
                </li>
            `;
        });

        let acoesHtml = '';
        let statusColor = '';
        if (pedido.status === 'recebido') {
            acoesHtml = `<button data-action="preparar" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">Iniciar Preparo</button>`;
            statusColor = 'border-red-500';
        } else if (pedido.status === 'preparando') {
            acoesHtml = `<button data-action="pronto" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">Pronto para Servir</button>`;
            statusColor = 'border-yellow-500';
        } else if (pedido.status === 'pronto') {
            acoesHtml = `<button data-action="finalizar" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">Finalizar Pedido</button>`;
            statusColor = 'border-green-500';
        }

        const tempoAtras = getTempoAtras(pedido.created_at);

        return `
            <div class="bg-white rounded-lg shadow-sm border-l-4 ${statusColor} p-4 hover:shadow-md transition-shadow duration-200" data-id="${pedido.id}">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center space-x-2">
                        <span class="font-bold text-lg text-gray-900">#${pedido.id}</span>
                        <span class="text-sm text-gray-500">•</span>
                        <span class="text-sm font-medium text-gray-700">Mesa ${pedido.mesa_numero || 'N/A'}</span>
                    </div>
                    <span class="text-xs text-gray-500">${tempoAtras}</span>
                </div>
                
                <div class="border-t border-b border-gray-100 py-3 my-3">
                    <ul class="space-y-1">${itensHtml}</ul>
                </div>
                
                ${pedido.observacoes ? `<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3">
                    <p class="text-sm text-yellow-800"><i class="fas fa-exclamation-triangle mr-1"></i><b>Obs:</b> ${pedido.observacoes}</p>
                </div>` : ''}
                
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm text-gray-600">Total:</span>
                    <span class="text-lg font-bold text-gray-900">R$ ${parseFloat(pedido.total).toFixed(2).replace('.', ',')}</span>
                </div>
                
                <div class="order-actions">${acoesHtml}</div>
            </div>
        `;
    }

    function getTempoAtras(data) {
        const agora = new Date();
        const pedidoData = new Date(data);
        const diffMinutos = Math.floor((agora - pedidoData) / (1000 * 60));
        
        if (diffMinutos < 1) return 'há poucos segundos';
        if (diffMinutos < 60) return `há ${diffMinutos} min`;
        
        const diffHoras = Math.floor(diffMinutos / 60);
        if (diffHoras < 24) return `há ${diffHoras}h`;
        
        const diffDias = Math.floor(diffHoras / 24);
        return `há ${diffDias} dias`;
    }

    async function fetchPedidos() {
        try {
            statusEl.classList.remove('bg-red-500');
            statusEl.classList.add('bg-green-500');
            document.getElementById('connection-text').textContent = 'Conectado';

            const response = await fetch('<?= URL_BASE ?>/admin/getPedidosAjax');
            if (!response.ok) throw new Error('Network response was not ok');
            
            const pedidos = await response.json();
            
            // Limpa as colunas
            Object.values(colunas).forEach(col => col.innerHTML = '');
            
            let currentOrderIds = new Set();
            pedidos.forEach(pedido => {
                currentOrderIds.add(pedido.id);
                const colunaDestino = colunas[pedido.status];
                if (colunaDestino) {
                    colunaDestino.innerHTML += renderizarPedido(pedido);
                }
            });

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
        }
    }

    async function atualizarStatus(pedidoId, novoStatus) {
        try {
            const response = await fetch(`<?= URL_BASE ?>/admin/mudarStatusPedido/${pedidoId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
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

    fetchPedidos(); // Carga inicial
    setInterval(fetchPedidos, 10000); // Polling a cada 10 segundos
});
</script>

<?php require_once '../app/views/admin/partials/footer.php'; ?>
