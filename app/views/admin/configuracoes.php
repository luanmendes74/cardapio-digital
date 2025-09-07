<?php require_once '../app/views/admin/partials/header.php'; ?>

<div class="container mx-auto">
    <?php require_once '../app/views/admin/partials/back_button.php'; ?>
    
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-700">Configurações do Estabelecimento</h2>
    </div>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['success_message'] ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($dados['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= $dados['error_message'] ?>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="/admin/configuracoes" method="post" enctype="multipart/form-data">
            
            <!-- Informações Básicas -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nome">Nome do Estabelecimento</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="nome" name="nome" type="text" value="<?= $dados['nome'] ?? '' ?>">
                <span class="text-red-500 text-xs italic"><?= $dados['nome_err'] ?? '' ?></span>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="descricao_curta">Descrição Curta (Ex: Culinária italiana autêntica)</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="descricao_curta" name="descricao_curta" rows="2"><?= $dados['descricao_curta'] ?? '' ?></textarea>
            </div>

            <!-- Contatos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="telefone">Telefone</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="telefone" name="telefone" type="text" value="<?= $dados['telefone'] ?? '' ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="endereco">Endereço</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="endereco" name="endereco" type="text" value="<?= $dados['endereco'] ?? '' ?>">
                </div>
            </div>

            <!-- Redes Sociais -->
             <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="whatsapp">WhatsApp (só números, ex: 5511987654321)</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="whatsapp" name="whatsapp" type="text" value="<?= $dados['whatsapp'] ?? '' ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="instagram">Instagram (só o nome de utilizador)</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="instagram" name="instagram" type="text" value="<?= $dados['instagram'] ?? '' ?>">
                </div>
                 <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="facebook">Facebook (só o nome de utilizador)</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="facebook" name="facebook" type="text" value="<?= $dados['facebook'] ?? '' ?>">
                </div>
            </div>


            <!-- Cores -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                 <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cor_primaria">Fundo Cabeçalho</label>
                    <input class="shadow appearance-none border rounded w-full h-10 px-1" id="cor_primaria" name="cor_primaria" type="color" value="<?= $dados['cor_primaria'] ?? '#dc2626' ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cor_texto_header">Texto Cabeçalho</label>
                    <input class="shadow appearance-none border rounded w-full h-10 px-1" id="cor_texto_header" name="cor_texto_header" type="color" value="<?= $dados['cor_texto_header'] ?? '#FFFFFF' ?>">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cor_secundaria">Botões "Adicionar"</label>
                    <input class="shadow appearance-none border rounded w-full h-10 px-1" id="cor_secundaria" name="cor_secundaria" type="color" value="<?= $dados['cor_secundaria'] ?? '#1d4ed8' ?>">
                </div>
                 <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cor_botao_pedido">Botão "Fazer Pedido"</label>
                    <input class="shadow appearance-none border rounded w-full h-10 px-1" id="cor_botao_pedido" name="cor_botao_pedido" type="color" value="<?= $dados['cor_botao_pedido'] ?? '#FFA500' ?>">
                </div>
            </div>

            <!-- Upload de Logo -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="logo">Logótipo (substituir o atual)</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="logo" name="logo" type="file">
                <span class="text-red-500 text-xs italic"><?= $dados['logo_err'] ?? '' ?></span>
                <?php if(!empty($dados['logo_atual'])): ?>
                    <p class="text-xs text-gray-600 mt-2">Logótipo atual:</p>
                    <img src="/uploads/<?= $dados['logo_atual'] ?>" alt="Logótipo Atual" class="w-24 h-24 object-contain rounded mt-1 border p-1">
                <?php endif; ?>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/admin/partials/footer.php'; ?>

