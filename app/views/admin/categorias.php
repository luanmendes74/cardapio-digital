<?php require_once '../app/views/admin/partials/header.php'; ?>

<div class="p-6">
    <?php require_once '../app/views/admin/partials/back_button.php'; ?>
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Gestão de Categorias</h2>
            <p class="text-gray-600 mt-1">Organize seus produtos em categorias</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-4">
            <a href="<?= URL_BASE ?>/admin/addcategoria" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Adicionar Categoria
            </a>
        </div>
    </div>

    <!-- Categorias Grid -->
    <?php if (empty($dados['categorias'])): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-tags text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Nenhuma categoria encontrada</h3>
            <p class="text-gray-500 mb-6">Comece criando categorias para organizar seus produtos</p>
            <a href="<?= URL_BASE ?>/admin/addcategoria" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200 inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Criar Primeira Categoria
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach($dados['categorias'] as $categoria): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <!-- Ícone da Categoria -->
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-24 flex items-center justify-center">
                        <i class="fas fa-tag text-3xl text-white"></i>
                    </div>
                    
                    <!-- Conteúdo do Card -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-lg font-bold text-gray-900 truncate"><?= htmlspecialchars($categoria->nome) ?></h3>
                        </div>
                        
                        <?php if(!empty($categoria->descricao)): ?>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2"><?= htmlspecialchars($categoria->descricao) ?></p>
                        <?php endif; ?>
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-sort mr-2"></i>
                                <span>Ordem: <?= htmlspecialchars($categoria->ordem) ?></span>
                            </div>
                        </div>
                        
                        <!-- Ações -->
                        <div class="flex items-center space-x-2">
                            <a href="<?= URL_BASE ?>/admin/editcategoria/<?= $categoria->id ?>" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                                <i class="fas fa-edit mr-2"></i>
                                Editar
                            </a>
                            <form action="<?= URL_BASE ?>/admin/deletecategoria/<?= $categoria->id ?>" method="post" class="flex-1" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria? Todos os produtos associados serão excluídos também!');">
                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-trash mr-2"></i>
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../app/views/admin/partials/footer.php'; ?>