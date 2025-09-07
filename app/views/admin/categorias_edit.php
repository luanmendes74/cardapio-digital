<?php require_once '../app/views/admin/partials/header.php'; ?>

<div class="p-6">
    <?php require_once '../app/views/admin/partials/back_button.php'; ?>
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Editar Categoria</h2>
            <p class="text-gray-600 mt-1">Atualize as informações da categoria</p>
        </div>
    </div>

    <!-- Formulário -->
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <form action="<?= URL_BASE ?>/admin/editcategoria/<?= $dados['id'] ?>" method="post">
                <div class="space-y-6">
                    <!-- Nome da Categoria -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome da Categoria <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nome" 
                            name="nome" 
                            value="<?= htmlspecialchars($dados['nome'] ?? '') ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 <?= (!empty($dados['nome_err'])) ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' ?>"
                            placeholder="Ex: Pizzas Salgadas"
                            required
                        >
                        <?php if (!empty($dados['nome_err'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $dados['nome_err'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                            Descrição (opcional)
                        </label>
                        <textarea 
                            id="descricao" 
                            name="descricao" 
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            placeholder="Uma breve descrição sobre a categoria"
                        ><?= htmlspecialchars($dados['descricao'] ?? '') ?></textarea>
                    </div>

                    <!-- Ordem -->
                    <div>
                        <label for="ordem" class="block text-sm font-medium text-gray-700 mb-2">
                            Ordem de Exibição
                        </label>
                        <input 
                            type="number" 
                            id="ordem" 
                            name="ordem" 
                            value="<?= htmlspecialchars($dados['ordem'] ?? '0') ?>"
                            min="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            placeholder="0"
                        >
                        <p class="mt-1 text-sm text-gray-500">Categorias com menor número aparecem primeiro</p>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="<?= URL_BASE ?>/admin/categorias" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../app/views/admin/partials/footer.php'; ?>