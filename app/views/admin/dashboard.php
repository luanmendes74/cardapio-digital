<?php require_once '../app/views/admin/partials/header.php'; ?>

<div class="space-y-6">
    <!-- Relatórios em Tempo Real -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Pedidos de Hoje -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-800">Pedidos de Hoje</h3>
                <i class="fas fa-clipboard-list text-lg text-blue-500"></i>
            </div>
            <div class="text-2xl font-bold text-blue-600 mb-1" id="pedidos-hoje">-</div>
            <p class="text-xs text-gray-500">Total de pedidos recebidos hoje</p>
            <div class="mt-2">
                <div class="flex items-center text-xs text-green-600">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span id="crescimento-pedidos">+0%</span>
                    <span class="text-gray-500 ml-1">vs ontem</span>
                </div>
            </div>
        </div>

        <!-- Total de Mesas Ocupadas -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-800">Mesas Ocupadas</h3>
                <i class="fas fa-chair text-lg text-green-500"></i>
            </div>
            <div class="text-2xl font-bold text-green-600 mb-1" id="mesas-ocupadas">-</div>
            <p class="text-xs text-gray-500">Mesas com pedidos ativos</p>
            <div class="mt-2">
                <div class="flex items-center text-xs text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    <span id="total-mesas">Total: -</span>
                </div>
            </div>
        </div>

        <!-- Novos Pedidos (Alerta) -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-800">Novos Pedidos</h3>
                <i class="fas fa-bell text-lg text-red-500 animate-pulse"></i>
            </div>
            <div class="text-2xl font-bold text-red-600 mb-1" id="novos-pedidos">-</div>
            <p class="text-xs text-gray-500">Aguardando preparo</p>
            <div class="mt-2">
                <div class="flex items-center text-xs text-red-600">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <span id="alerta-texto">Nenhum pedido pendente</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Acesso Rápido -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Acesso Rápido</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <a href="<?= URL_BASE ?>/admin/pedidos" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 flex flex-col items-center justify-center text-center">
                <i class="fas fa-clipboard-list text-2xl mb-2"></i>
                <span class="text-sm">Pedidos Mesa</span>
            </a>
            
            <a href="<?= URL_BASE ?>/admin/pedidosDelivery" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 flex flex-col items-center justify-center text-center">
                <i class="fas fa-motorcycle text-2xl mb-2"></i>
                <span class="text-sm">Pedidos Delivery</span>
            </a>
            
            <a href="<?= URL_BASE ?>/admin/produtos" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 flex flex-col items-center justify-center text-center">
                <i class="fas fa-box text-2xl mb-2"></i>
                <span class="text-sm">Produtos</span>
            </a>
            
            <a href="<?= URL_BASE ?>/admin/categorias" class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 flex flex-col items-center justify-center text-center">
                <i class="fas fa-tags text-2xl mb-2"></i>
                <span class="text-sm">Categorias</span>
            </a>
            
            <a href="<?= URL_BASE ?>/admin/mesas" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 flex flex-col items-center justify-center text-center">
                <i class="fas fa-chair text-2xl mb-2"></i>
                <span class="text-sm">Mesas</span>
            </a>
            
            <a href="<?= URL_BASE ?>/admin/delivery" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 flex flex-col items-center justify-center text-center">
                <i class="fas fa-motorcycle text-2xl mb-2"></i>
                <span class="text-sm">Config Delivery</span>
            </a>
            
            <a href="<?= URL_BASE ?>/admin/configuracoes" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 flex flex-col items-center justify-center text-center">
                <i class="fas fa-cog text-2xl mb-2"></i>
                <span class="text-sm">Configurações</span>
            </a>
        </div>
    </div>
</div>

<script>
// Atualizar hora atual
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('pt-BR', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    const timeElement = document.getElementById('current-time');
    if (timeElement) {
        timeElement.textContent = timeString;
    }
}

// Carregar dados do dashboard
async function loadDashboardData() {
    try {
        const response = await fetch('<?= URL_BASE ?>/admin/getDashboardStats');
        const data = await response.json();
        
        if (data.erro) {
            console.error('Erro ao carregar dados:', data.erro);
            return;
        }
        
        // Atualizar pedidos de hoje
        document.getElementById('pedidos-hoje').textContent = data.pedidos_hoje || 0;
        
        // Atualizar crescimento
        const crescimento = data.crescimento_pedidos || 0;
        const crescimentoElement = document.getElementById('crescimento-pedidos');
        if (crescimento > 0) {
            crescimentoElement.textContent = `+${crescimento}%`;
            crescimentoElement.className = 'flex items-center text-sm text-green-600';
        } else if (crescimento < 0) {
            crescimentoElement.textContent = `${crescimento}%`;
            crescimentoElement.className = 'flex items-center text-sm text-red-600';
        } else {
            crescimentoElement.textContent = '0%';
            crescimentoElement.className = 'flex items-center text-sm text-gray-600';
        }
        
        // Atualizar mesas ocupadas
        document.getElementById('mesas-ocupadas').textContent = data.mesas_ocupadas || 0;
        
        // Atualizar total de mesas
        document.getElementById('total-mesas').textContent = `Total: ${data.mesas_ativas || 0}`;
        
        // Atualizar novos pedidos (alerta)
        const novosPedidos = data.novos_pedidos || 0;
        document.getElementById('novos-pedidos').textContent = novosPedidos;
        
        // Atualizar texto do alerta
        const alertaTexto = document.getElementById('alerta-texto');
        if (novosPedidos > 0) {
            alertaTexto.textContent = `${novosPedidos} pedido(s) aguardando preparo`;
            alertaTexto.className = 'flex items-center text-sm text-red-600';
        } else {
            alertaTexto.textContent = 'Nenhum pedido pendente';
            alertaTexto.className = 'flex items-center text-sm text-green-600';
        }
        
    } catch (error) {
        console.error('Erro ao carregar dados do dashboard:', error);
    }
}

// Atualizar a cada 30 segundos
setInterval(updateTime, 1000);
setInterval(loadDashboardData, 30000);
updateTime(); // Executar imediatamente
loadDashboardData(); // Carregar dados imediatamente
</script>

<?php require_once '../app/views/admin/partials/footer.php'; ?>