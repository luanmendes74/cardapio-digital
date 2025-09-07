<?php require_once '../app/views/admin/partials/header.php'; ?>

<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Configurações de Delivery</h2>
            <p class="text-gray-600 mt-1">Configure o sistema de entrega do seu estabelecimento</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-4">
            <?php if($dados['configuracao'] && $dados['configuracao']->ativo): ?>
                <a href="<?= URL_BASE ?>/delivery/cardapio/<?= $dados['estabelecimento']->id ?>" 
                   target="_blank"
                   class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Ver Cardápio Delivery
                </a>
                <form method="POST" action="<?= URL_BASE ?>/admin/toggleDelivery" class="inline">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Desativar Delivery
                    </button>
                </form>
            <?php else: ?>
                <form method="POST" action="<?= URL_BASE ?>/admin/toggleDelivery" class="inline">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <i class="fas fa-check mr-2"></i>
                        Ativar Delivery
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php flash('delivery_mensagem'); ?>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Status do Delivery -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status do Delivery</h3>
            <div class="flex items-center">
                <?php if($dados['configuracao'] && $dados['configuracao']->ativo): ?>
                    <div class="flex items-center text-green-600">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-lg">Delivery Ativo</p>
                            <p class="text-sm text-gray-600">Seus clientes podem fazer pedidos para entrega</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex items-center text-red-600">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-times-circle text-2xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-lg">Delivery Inativo</p>
                            <p class="text-sm text-gray-600">Apenas pedidos para mesa estão disponíveis</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Resumo das Configurações -->
        <?php if($dados['configuracao']): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Resumo das Configurações</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Taxa de Entrega:</span>
                    <span class="font-bold text-lg text-gray-900">R$ <?= number_format($dados['configuracao']->taxa_entrega, 2, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Valor Mínimo:</span>
                    <span class="font-bold text-lg text-gray-900">R$ <?= number_format($dados['configuracao']->valor_minimo_pedido, 2, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-600">Tempo Estimado:</span>
                    <span class="font-bold text-lg text-gray-900"><?= $dados['configuracao']->tempo_estimado_min ?>-<?= $dados['configuracao']->tempo_estimado_max ?> min</span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Formulário de Configuração -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Configurações de Delivery</h3>
        
        <form action="<?= URL_BASE ?>/admin/salvarConfiguracaoDelivery" method="post" class="space-y-6">
            
            <!-- Ativar Delivery -->
            <div class="bg-gray-50 rounded-lg p-4">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="ativo" class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500" 
                           <?= ($dados['configuracao'] && $dados['configuracao']->ativo) ? 'checked' : '' ?>>
                    <span class="ml-3 text-gray-700 font-medium text-lg">Ativar sistema de delivery</span>
                </label>
                <p class="text-sm text-gray-500 mt-2 ml-8">Marque esta opção para permitir pedidos de entrega</p>
            </div>

            <!-- Taxa de Entrega e Valor Mínimo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="taxa_entrega">
                        Taxa de Entrega (R$)
                    </label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                           id="taxa_entrega" name="taxa_entrega" type="number" step="0.01" 
                           value="<?= $dados['configuracao']->taxa_entrega ?? '5.00' ?>"
                           placeholder="0.00">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="valor_minimo_pedido">
                        Valor Mínimo do Pedido (R$)
                    </label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                           id="valor_minimo_pedido" name="valor_minimo_pedido" type="number" step="0.01" 
                           value="<?= $dados['configuracao']->valor_minimo_pedido ?? '20.00' ?>"
                           placeholder="0.00">
                </div>
            </div>

            <!-- Tempo Estimado -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tempo_estimado_min">
                        Tempo Mínimo (minutos)
                    </label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                           id="tempo_estimado_min" name="tempo_estimado_min" type="number" 
                           value="<?= $dados['configuracao']->tempo_estimado_min ?? '30' ?>"
                           placeholder="30">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tempo_estimado_max">
                        Tempo Máximo (minutos)
                    </label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                           id="tempo_estimado_max" name="tempo_estimado_max" type="number" 
                           value="<?= $dados['configuracao']->tempo_estimado_max ?? '60' ?>"
                           placeholder="60">
                </div>
            </div>

            <!-- Bairros Atendidos -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="bairros_atendidos">
                    Bairros Atendidos
                </label>
                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                          id="bairros_atendidos" name="bairros_atendidos" rows="3" 
                          placeholder="Ex: Centro, Zona Sul, Zona Norte, Jardim das Flores"><?= htmlspecialchars($dados['configuracao']->bairros_atendidos ?? '') ?></textarea>
                <p class="text-gray-500 text-sm mt-2">Separe os bairros por vírgula</p>
            </div>

            <!-- Horário de Funcionamento -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="horario_funcionamento">
                    Horário de Funcionamento para Delivery
                </label>
                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                          id="horario_funcionamento" name="horario_funcionamento" rows="3" 
                          placeholder="Ex: Segunda a Sexta: 11h às 22h, Sábado e Domingo: 11h às 23h"><?= htmlspecialchars($dados['configuracao']->horario_funcionamento ?? '') ?></textarea>
            </div>

            <!-- Observações -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="observacoes">
                    Observações Adicionais
                </label>
                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                          id="observacoes" name="observacoes" rows="3" 
                          placeholder="Ex: Não entregamos em dias de chuva forte, Pedidos acima de R$ 50 têm frete grátis"><?= htmlspecialchars($dados['configuracao']->observacoes ?? '') ?></textarea>
            </div>

            <!-- Botão de Submissão -->
            <div class="flex justify-end">
                <button class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-lg transition-colors duration-200 flex items-center" type="submit">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Configurações
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/admin/partials/footer.php'; ?>

<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Configurações de Delivery</h2>
            <p class="text-gray-600 mt-1">Configure o sistema de entrega do seu estabelecimento</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-4">
            <?php if($dados['configuracao'] && $dados['configuracao']->ativo): ?>
                <a href="<?= URL_BASE ?>/delivery/cardapio/<?= $dados['estabelecimento']->id ?>" 
                   target="_blank"
                   class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Ver Cardápio Delivery
                </a>
                <form method="POST" action="<?= URL_BASE ?>/admin/toggleDelivery" class="inline">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Desativar Delivery
                    </button>
                </form>
            <?php else: ?>
                <form method="POST" action="<?= URL_BASE ?>/admin/toggleDelivery" class="inline">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <i class="fas fa-check mr-2"></i>
                        Ativar Delivery
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php flash('delivery_mensagem'); ?>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Status do Delivery -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status do Delivery</h3>
            <div class="flex items-center">
                <?php if($dados['configuracao'] && $dados['configuracao']->ativo): ?>
                    <div class="flex items-center text-green-600">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-lg">Delivery Ativo</p>
                            <p class="text-sm text-gray-600">Seus clientes podem fazer pedidos para entrega</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex items-center text-red-600">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-times-circle text-2xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-lg">Delivery Inativo</p>
                            <p class="text-sm text-gray-600">Apenas pedidos para mesa estão disponíveis</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Resumo das Configurações -->
        <?php if($dados['configuracao']): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Resumo das Configurações</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Taxa de Entrega:</span>
                    <span class="font-bold text-lg text-gray-900">R$ <?= number_format($dados['configuracao']->taxa_entrega, 2, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Valor Mínimo:</span>
                    <span class="font-bold text-lg text-gray-900">R$ <?= number_format($dados['configuracao']->valor_minimo_pedido, 2, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-600">Tempo Estimado:</span>
                    <span class="font-bold text-lg text-gray-900"><?= $dados['configuracao']->tempo_estimado_min ?>-<?= $dados['configuracao']->tempo_estimado_max ?> min</span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Formulário de Configuração -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Configurações de Delivery</h3>
        
        <form action="<?= URL_BASE ?>/admin/salvarConfiguracaoDelivery" method="post" class="space-y-6">
            
            <!-- Ativar Delivery -->
            <div class="bg-gray-50 rounded-lg p-4">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="ativo" class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500" 
                           <?= ($dados['configuracao'] && $dados['configuracao']->ativo) ? 'checked' : '' ?>>
                    <span class="ml-3 text-gray-700 font-medium text-lg">Ativar sistema de delivery</span>
                </label>
                <p class="text-sm text-gray-500 mt-2 ml-8">Marque esta opção para permitir pedidos de entrega</p>
            </div>

            <!-- Taxa de Entrega e Valor Mínimo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="taxa_entrega">
                        Taxa de Entrega (R$)
                    </label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                           id="taxa_entrega" name="taxa_entrega" type="number" step="0.01" 
                           value="<?= $dados['configuracao']->taxa_entrega ?? '5.00' ?>"
                           placeholder="0.00">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="valor_minimo_pedido">
                        Valor Mínimo do Pedido (R$)
                    </label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                           id="valor_minimo_pedido" name="valor_minimo_pedido" type="number" step="0.01" 
                           value="<?= $dados['configuracao']->valor_minimo_pedido ?? '20.00' ?>"
                           placeholder="0.00">
                </div>
            </div>

            <!-- Tempo Estimado -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tempo_estimado_min">
                        Tempo Mínimo (minutos)
                    </label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                           id="tempo_estimado_min" name="tempo_estimado_min" type="number" 
                           value="<?= $dados['configuracao']->tempo_estimado_min ?? '30' ?>"
                           placeholder="30">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tempo_estimado_max">
                        Tempo Máximo (minutos)
                    </label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                           id="tempo_estimado_max" name="tempo_estimado_max" type="number" 
                           value="<?= $dados['configuracao']->tempo_estimado_max ?? '60' ?>"
                           placeholder="60">
                </div>
            </div>

            <!-- Bairros Atendidos -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="bairros_atendidos">
                    Bairros Atendidos
                </label>
                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                          id="bairros_atendidos" name="bairros_atendidos" rows="3" 
                          placeholder="Ex: Centro, Zona Sul, Zona Norte, Jardim das Flores"><?= htmlspecialchars($dados['configuracao']->bairros_atendidos ?? '') ?></textarea>
                <p class="text-gray-500 text-sm mt-2">Separe os bairros por vírgula</p>
            </div>

            <!-- Horário de Funcionamento -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="horario_funcionamento">
                    Horário de Funcionamento para Delivery
                </label>
                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                          id="horario_funcionamento" name="horario_funcionamento" rows="3" 
                          placeholder="Ex: Segunda a Sexta: 11h às 22h, Sábado e Domingo: 11h às 23h"><?= htmlspecialchars($dados['configuracao']->horario_funcionamento ?? '') ?></textarea>
            </div>

            <!-- Observações -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="observacoes">
                    Observações Adicionais
                </label>
                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                          id="observacoes" name="observacoes" rows="3" 
                          placeholder="Ex: Não entregamos em dias de chuva forte, Pedidos acima de R$ 50 têm frete grátis"><?= htmlspecialchars($dados['configuracao']->observacoes ?? '') ?></textarea>
            </div>

            <!-- Botão de Submissão -->
            <div class="flex justify-end">
                <button class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-lg transition-colors duration-200 flex items-center" type="submit">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Configurações
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/admin/partials/footer.php'; ?>

