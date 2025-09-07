<?php require_once '../app/views/superadmin/partials/header.php'; ?>

<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-700">Editar Plano</h2>
        <a href="<?= URL_BASE ?>/SuperAdmin/planos" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Voltar para a Lista
        </a>
    </div>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="<?= URL_BASE ?>/SuperAdmin/editPlano/<?= $dados['id'] ?>" method="post">
            
            <!-- Nome do Plano -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nome">
                    Nome do Plano
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?= (!empty($dados['nome_err'])) ? 'border-red-500' : ''; ?>" 
                       id="nome" name="nome" type="text" placeholder="Ex: Plano Básico" 
                       value="<?= htmlspecialchars($dados['nome'] ?? '') ?>">
                <span class="text-red-500 text-xs italic"><?= $dados['nome_err'] ?? '' ?></span>
            </div>

            <!-- Preço Mensal -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="preco_mensal">
                    Preço Mensal (R$)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?= (!empty($dados['preco_mensal_err'])) ? 'border-red-500' : ''; ?>" 
                       id="preco_mensal" name="preco_mensal" type="number" step="0.01" placeholder="Ex: 29.90" 
                       value="<?= htmlspecialchars($dados['preco_mensal'] ?? '') ?>">
                <span class="text-red-500 text-xs italic"><?= $dados['preco_mensal_err'] ?? '' ?></span>
            </div>

            <!-- Limites -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="limite_produtos">
                        Limite de Produtos
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="limite_produtos" name="limite_produtos" type="number" placeholder="Deixe vazio para ilimitado" 
                           value="<?= htmlspecialchars($dados['limite_produtos'] ?? '') ?>">
                    <p class="text-gray-500 text-xs mt-1">Deixe vazio para produtos ilimitados</p>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="limite_mesas">
                        Limite de Mesas
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="limite_mesas" name="limite_mesas" type="number" placeholder="Deixe vazio para ilimitado" 
                           value="<?= htmlspecialchars($dados['limite_mesas'] ?? '') ?>">
                    <p class="text-gray-500 text-xs mt-1">Deixe vazio para mesas ilimitadas</p>
                </div>
            </div>

            <!-- Recursos -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="recursos">
                    Recursos Inclusos
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                          id="recursos" name="recursos" rows="4" 
                          placeholder="Ex: Cardápio digital, QR codes, Painel de pedidos, Suporte 24/7"><?= htmlspecialchars($dados['recursos'] ?? '') ?></textarea>
                <p class="text-gray-500 text-xs mt-1">Liste os recursos inclusos no plano, separados por vírgula</p>
            </div>

            <!-- Botão de Submissão -->
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    <i class="fas fa-save mr-2"></i> Atualizar Plano
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/superadmin/partials/footer.php'; ?>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="limite_mesas">
                        Limite de Mesas
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="limite_mesas" name="limite_mesas" type="number" placeholder="Deixe vazio para ilimitado" 
                           value="<?= htmlspecialchars($dados['limite_mesas'] ?? '') ?>">
                    <p class="text-gray-500 text-xs mt-1">Deixe vazio para mesas ilimitadas</p>
                </div>
            </div>

            <!-- Recursos -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="recursos">
                    Recursos Inclusos
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                          id="recursos" name="recursos" rows="4" 
                          placeholder="Ex: Cardápio digital, QR codes, Painel de pedidos, Suporte 24/7"><?= htmlspecialchars($dados['recursos'] ?? '') ?></textarea>
                <p class="text-gray-500 text-xs mt-1">Liste os recursos inclusos no plano, separados por vírgula</p>
            </div>

            <!-- Botão de Submissão -->
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    <i class="fas fa-save mr-2"></i> Atualizar Plano
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/superadmin/partials/footer.php'; ?>
