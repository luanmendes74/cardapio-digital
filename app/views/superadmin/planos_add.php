<?php require_once '../app/views/superadmin/partials/header.php'; ?>

<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-700">Adicionar Novo Plano</h2>
        <a href="<?= URL_BASE ?>/SuperAdmin/planos" class="text-blue-500 hover:text-blue-700">
            &larr; Voltar para a Lista
        </a>
    </div>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="<?= URL_BASE ?>/SuperAdmin/addPlano" method="post">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nome">Nome do Plano</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="nome" name="nome" type="text" value="<?= $dados['nome'] ?? '' ?>">
                <span class="text-red-500 text-xs italic"><?= $dados['nome_err'] ?? '' ?></span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="preco_mensal">Preço Mensal (ex: 29.90)</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="preco_mensal" name="preco_mensal" type="text" value="<?= $dados['preco_mensal'] ?? '' ?>">
                    <span class="text-red-500 text-xs italic"><?= $dados['preco_mensal_err'] ?? '' ?></span>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="limite_produtos">Limite de Produtos</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="limite_produtos" name="limite_produtos" type="number" value="<?= $dados['limite_produtos'] ?? '' ?>">
                </div>
                 <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="limite_mesas">Limite de Mesas</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="limite_mesas" name="limite_mesas" type="number" value="<?= $dados['limite_mesas'] ?? '' ?>">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="recursos">Recursos (separados por ponto e vírgula)</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="recursos" name="recursos" rows="3"><?= $dados['recursos'] ?? '' ?></textarea>
            </div>
            
            <div class="flex items-center justify-between mt-6">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
                    Criar Plano
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/superadmin/partials/footer.php'; ?>
