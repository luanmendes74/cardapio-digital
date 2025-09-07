<?php require_once '../app/views/superadmin/partials/header.php'; ?>

<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-700">Editar Estabelecimento</h2>
        <a href="<?= URL_BASE ?>/SuperAdmin" class="text-blue-500 hover:text-blue-700">
            &larr; Voltar para a Lista
        </a>
    </div>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="<?= URL_BASE ?>/SuperAdmin/editEstabelecimento/<?= $dados['id'] ?>" method="post">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nome">Nome do Estabelecimento</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="nome" name="nome" type="text" value="<?= $dados['nome'] ?? '' ?>">
                    <span class="text-red-500 text-xs italic"><?= $dados['nome_err'] ?? '' ?></span>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="subdominio">Subdomínio / Caminho</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="subdominio" name="subdominio" type="text" value="<?= $dados['subdominio'] ?? '' ?>">
                    <span class="text-red-500 text-xs italic"><?= $dados['subdominio_err'] ?? '' ?></span>
                </div>
            </div>
            
            <div class="flex items-center justify-between mt-6">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
                    Atualizar Estabelecimento
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/superadmin/partials/footer.php'; ?>

