<?php require_once '../app/views/admin/partials/header.php'; ?>

<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-700">Editar Produto</h2>
        <a href="/admin/produtos" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Voltar para a Lista
        </a>
    </div>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="/admin/editproduto/<?= $dados['id'] ?>" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nome">
                    Nome do Produto
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?= (!empty($dados['nome_err'])) ? 'border-red-500' : ''; ?>" id="nome" name="nome" type="text" value="<?= $dados['nome'] ?? '' ?>">
                <span class="text-red-500 text-xs italic"><?= $dados['nome_err'] ?? '' ?></span>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="descricao">
                    Descrição
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="descricao" name="descricao"><?= $dados['descricao'] ?? '' ?></textarea>
            </div>

             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="preco">
                        Preço
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?= (!empty($dados['preco_err'])) ? 'border-red-500' : ''; ?>" id="preco" name="preco" type="text" value="<?= number_format($dados['preco'] ?? 0, 2, ',', '.') ?>">
                    <span class="text-red-500 text-xs italic"><?= $dados['preco_err'] ?? '' ?></span>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="categoria_id">
                        Categoria
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="categoria_id" name="categoria_id">
                        <?php foreach($dados['categorias'] as $categoria): ?>
                            <option value="<?= $categoria->id ?>" <?= ($categoria->id == $dados['categoria_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categoria->nome) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="imagem">
                    Alterar Imagem do Produto (opcional)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="imagem" name="imagem" type="file">
                <span class="text-red-500 text-xs italic"><?= $dados['imagem_err'] ?? '' ?></span>
                <?php if(!empty($dados['imagem_atual'])): ?>
                    <p class="text-xs text-gray-600 mt-2">Imagem atual:</p>
                    <img src="/uploads/<?= $dados['imagem_atual'] ?>" alt="Imagem Atual" class="w-24 h-24 object-cover rounded mt-1">
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Disponível?</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio" name="disponivel" value="1" <?= ($dados['disponivel'] == 1) ? 'checked' : '' ?>>
                            <span class="ml-2">Sim</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" class="form-radio" name="disponivel" value="0" <?= ($dados['disponivel'] == 0) ? 'checked' : '' ?>>
                            <span class="ml-2">Não</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Disponível para Delivery?</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio" name="disponivel_delivery" value="1" <?= (($dados['disponivel_delivery'] ?? 1) == 1) ? 'checked' : '' ?>>
                            <span class="ml-2">Sim</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" class="form-radio" name="disponivel_delivery" value="0" <?= (($dados['disponivel_delivery'] ?? 1) == 0) ? 'checked' : '' ?>>
                            <span class="ml-2">Não</span>
                        </label>
                    </div>
                </div>
                 <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Mais Pedido (Destaque)?</label>
                     <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" class="form-checkbox h-5 w-5" name="destaque" value="1" <?= ($dados['destaque'] == 1) ? 'checked' : '' ?>>
                            <span class="ml-2 text-gray-700">Sim, marcar como destaque</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Atualizar Produto
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/admin/partials/footer.php'; ?>

