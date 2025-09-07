<?php require_once '../app/views/admin/partials/header.php'; ?>

<div class="p-6">
    <?php require_once '../app/views/admin/partials/back_button.php'; ?>
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Gestão de Produtos</h2>
            <p class="text-gray-600 mt-1">Gerencie o cardápio do seu estabelecimento</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-4">
            <a href="<?= URL_BASE ?>/admin/addproduto" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Adicionar Produto
            </a>
        </div>
    </div>

    <!-- Produtos Grid -->
    <?php if (empty($dados['produtos'])): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-box text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Nenhum produto encontrado</h3>
            <p class="text-gray-500 mb-6">Comece adicionando produtos ao seu cardápio</p>
            <a href="<?= URL_BASE ?>/admin/addproduto" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200 inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Adicionar Primeiro Produto
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
            <?php foreach($dados['produtos'] as $produto): ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <!-- Imagem do Produto -->
                    <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                        <?php if(!empty($produto->imagem)): ?>
                            <img src="<?= URL_BASE ?>/uploads/<?= htmlspecialchars($produto->imagem) ?>" alt="<?= htmlspecialchars($produto->nome) ?>" class="w-full h-24 object-cover">
                        <?php else: ?>
                            <div class="w-full h-24 bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-image text-lg text-gray-300"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Conteúdo do Card -->
                    <div class="p-2">
                        <div class="flex items-start justify-between mb-1">
                            <h3 class="text-xs font-bold text-gray-900 truncate"><?= htmlspecialchars($produto->nome) ?></h3>
                            <?php if($produto->destaque): ?>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-1 py-0.5 rounded-full">Destaque</span>
                            <?php endif; ?>
                        </div>
                        
                        <p class="text-xs text-gray-600 mb-1 line-clamp-1"><?= htmlspecialchars($produto->descricao ?? '') ?></p>
                        
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-gray-900">R$ <?= number_format($produto->preco, 2, ',', '.') ?></span>
                            <div class="flex flex-col space-y-0.5">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium <?= $produto->disponivel ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <div class="w-1 h-1 rounded-full <?= $produto->disponivel ? 'bg-green-400' : 'bg-red-400' ?> mr-1"></div>
                                    <?= $produto->disponivel ? 'Disponível' : 'Indisponível' ?>
                                </span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium <?= ($produto->disponivel_delivery ?? 1) ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' ?>">
                                    <i class="fas fa-motorcycle text-xs mr-1"></i>
                                    <?= ($produto->disponivel_delivery ?? 1) ? 'Delivery' : 'Sem Delivery' ?>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Ações -->
                        <div class="flex items-center space-x-1">
                            <a href="<?= URL_BASE ?>/admin/editproduto/<?= $produto->id ?>" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium py-1 px-1.5 rounded transition-colors duration-200 text-center">
                                <i class="fas fa-edit mr-1"></i>
                                Editar
                            </a>
                            <form action="<?= URL_BASE ?>/admin/deleteproduto/<?= $produto->id ?>" method="post" class="flex-1" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white text-xs font-medium py-1 px-1.5 rounded transition-colors duration-200">
                                    <i class="fas fa-trash mr-1"></i>
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

