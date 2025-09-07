<?php require_once '../app/views/superadmin/partials/header.php'; ?>

<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-700">Adicionar Novo Estabelecimento</h2>
        <a href="<?= URL_BASE ?>/SuperAdmin" class="text-blue-500 hover:text-blue-700">
            &larr; Voltar para a Lista
        </a>
    </div>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="<?= URL_BASE ?>/SuperAdmin/addEstabelecimento" method="post">
            <h3 class="text-lg font-semibold mb-4 text-gray-600 border-b pb-2">Dados do Estabelecimento</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nome">Nome do Estabelecimento</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="nome" name="nome" type="text" value="<?= $dados['nome'] ?? '' ?>">
                    <span class="text-red-500 text-xs italic"><?= $dados['nome_err'] ?? '' ?></span>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="subdominio">Subdomínio / Caminho (ex: pizzaria-top)</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="subdominio" name="subdominio" type="text" value="<?= $dados['subdominio'] ?? '' ?>">
                    <span class="text-red-500 text-xs italic"><?= $dados['subdominio_err'] ?? '' ?></span>
                </div>
            </div>

            <h3 class="text-lg font-semibold mt-6 mb-4 text-gray-600 border-b pb-2">Dados do Utilizador Admin</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                 <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="admin_nome">Nome do Admin</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="admin_nome" name="admin_nome" type="text" value="<?= $dados['admin_nome'] ?? '' ?>">
                    <span class="text-red-500 text-xs italic"><?= $dados['admin_nome_err'] ?? '' ?></span>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="admin_email">Email do Admin</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="admin_email" name="admin_email" type="email" value="<?= $dados['admin_email'] ?? '' ?>">
                    <span class="text-red-500 text-xs italic"><?= $dados['admin_email_err'] ?? '' ?></span>
                </div>
            </div>
             <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="admin_senha">Senha do Admin</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="admin_senha" name="admin_senha" type="password">
                <span class="text-red-500 text-xs italic"><?= $dados['admin_senha_err'] ?? '' ?></span>
            </div>
            
            <div class="flex items-center justify-between mt-6">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
                    Criar Estabelecimento e Utilizador
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/superadmin/partials/footer.php'; ?>
