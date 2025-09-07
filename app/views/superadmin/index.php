<?php require_once '../app/views/superadmin/partials/header.php'; ?>

<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-700">Gestão de Estabelecimentos</h2>
        <a href="<?= URL_BASE ?>/SuperAdmin/addEstabelecimento" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i> Adicionar Estabelecimento
        </a>
    </div>

    <?php echo flash('estabelecimento_mensagem'); ?>

    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nome</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Link do Cardápio</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados['estabelecimentos'] as $est): ?>
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                             <p class="text-gray-900 whitespace-no-wrap font-bold"><?= htmlspecialchars($est->nome); ?></p>
                             <p class="text-gray-600 whitespace-no-wrap text-xs"><?= htmlspecialchars($est->email); ?></p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <a href="<?= gerarUrlEstabelecimento($est->subdominio) ?>" target="_blank" class="text-blue-500 hover:underline">
                                <?= gerarUrlEstabelecimento($est->subdominio) ?>
                            </a>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="relative inline-block px-3 py-1 font-semibold <?= $est->ativo ? 'text-green-900' : 'text-red-900'; ?> leading-tight">
                                <span aria-hidden class="absolute inset-0 <?= $est->ativo ? 'bg-green-200' : 'bg-red-200'; ?> opacity-50 rounded-full"></span>
                                <span class="relative"><?= $est->ativo ? 'Ativo' : 'Inativo'; ?></span>
                            </span>
                        </td>
                         <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                            <div class="flex justify-end items-center space-x-2">
                                <a href="<?= URL_BASE ?>/SuperAdmin/editEstabelecimento/<?= $est->id; ?>" class="text-blue-600 hover:text-blue-900 p-2" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($est->ativo): ?>
                                    <form action="<?= URL_BASE ?>/SuperAdmin/desativar/<?= $est->id; ?>" method="POST" class="inline-block">
                                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-xs">Desativar</button>
                                    </form>
                                <?php else: ?>
                                    <form action="<?= URL_BASE ?>/SuperAdmin/ativar/<?= $est->id; ?>" method="POST" class="inline-block">
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">Ativar</button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?= URL_BASE ?>/SuperAdmin/deleteEstabelecimento/<?= $est->id; ?>" method="POST" class="inline-block" onsubmit="return confirm('ATENÇÃO: Isto irá apagar o estabelecimento e TODOS os seus dados. Deseja continuar?');">
                                    <button type="submit" class="text-red-600 hover:text-red-900 p-2" title="Apagar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../app/views/superadmin/partials/footer.php'; ?>

