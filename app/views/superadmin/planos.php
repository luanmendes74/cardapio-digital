<?php require_once '../app/views/superadmin/partials/header.php'; ?>

<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-700">Gestão de Planos</h2>
        <a href="<?= URL_BASE ?>/SuperAdmin/addPlano" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i> Adicionar Plano
        </a>
    </div>

    <?php flash('plano_mensagem'); ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($dados['planos'] as $plano): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($plano->nome) ?></h3>
                        <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-2.5 py-0.5 rounded">
                            R$ <?= number_format($plano->preco_mensal, 2, ',', '.') ?>/mês
                        </span>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-box text-gray-500 mr-3"></i>
                            <span class="text-gray-600">
                                <?= $plano->limite_produtos ? $plano->limite_produtos . ' produtos' : 'Produtos ilimitados' ?>
                            </span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-chair text-gray-500 mr-3"></i>
                            <span class="text-gray-600">
                                <?= $plano->limite_mesas ? $plano->limite_mesas . ' mesas' : 'Mesas ilimitadas' ?>
                            </span>
                        </div>
                        <?php if($plano->recursos): ?>
                            <div class="flex items-start">
                                <i class="fas fa-star text-gray-500 mr-3 mt-1"></i>
                                <span class="text-gray-600 text-sm"><?= htmlspecialchars($plano->recursos) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="<?= URL_BASE ?>/SuperAdmin/editPlano/<?= $plano->id ?>" 
                           class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded text-sm">
                            <i class="fas fa-edit mr-1"></i> Editar
                        </a>
                        <form method="POST" action="<?= URL_BASE ?>/SuperAdmin/deletePlano/<?= $plano->id ?>" 
                              class="flex-1" onsubmit="return confirm('Tem certeza que deseja excluir este plano?')">
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded text-sm">
                                <i class="fas fa-trash mr-1"></i> Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if(empty($dados['planos'])): ?>
        <div class="text-center py-12 bg-white rounded-lg shadow-sm">
            <i class="fas fa-credit-card text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Nenhum plano cadastrado</h3>
            <p class="text-gray-500 mb-4">Comece criando seu primeiro plano de assinatura.</p>
            <a href="<?= URL_BASE ?>/SuperAdmin/addPlano" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i> Criar Primeiro Plano
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../app/views/superadmin/partials/footer.php'; ?>
                              class="flex-1" onsubmit="return confirm('Tem certeza que deseja excluir este plano?')">
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded text-sm">
                                <i class="fas fa-trash mr-1"></i> Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if(empty($dados['planos'])): ?>
        <div class="text-center py-12 bg-white rounded-lg shadow-sm">
            <i class="fas fa-credit-card text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Nenhum plano cadastrado</h3>
            <p class="text-gray-500 mb-4">Comece criando seu primeiro plano de assinatura.</p>
            <a href="<?= URL_BASE ?>/SuperAdmin/addPlano" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i> Criar Primeiro Plano
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../app/views/superadmin/partials/footer.php'; ?>
    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nome do Plano</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Preço Mensal</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Limites</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados['planos'] as $plano): ?>
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap font-bold"><?= htmlspecialchars($plano->nome); ?></p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">R$ <?= number_format($plano->preco_mensal, 2, ',', '.'); ?></p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-600 whitespace-no-wrap text-xs">Produtos: <?= $plano->limite_produtos; ?></p>
                            <p class="text-gray-600 whitespace-no-wrap text-xs">Mesas: <?= $plano->limite_mesas; ?></p>
                        </td>
                         <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                             <a href="<?= URL_BASE ?>/SuperAdmin/editPlano/<?= $plano->id; ?>" class="text-blue-600 hover:text-blue-900 mr-3" title="Editar"><i class="fas fa-edit"></i></a>
                             <form action="<?= URL_BASE ?>/SuperAdmin/deletePlano/<?= $plano->id; ?>" method="POST" class="inline-block" onsubmit="return confirm('Tem a certeza que deseja apagar este plano?');">
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Apagar"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../app/views/superadmin/partials/footer.php'; ?>
