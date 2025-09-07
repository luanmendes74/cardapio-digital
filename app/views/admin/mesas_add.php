<?php require_once '../app/views/admin/partials/header.php'; ?>

<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-700">Adicionar Nova Mesa</h2>
        <a href="/admin/mesas" class="text-blue-500 hover:text-blue-700">
            &larr; Voltar para a Lista de Mesas
        </a>
    </div>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="/admin/addmesa" method="post">
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="numero">
                    Número da Mesa
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?= (!empty($dados['numero_err'])) ? 'border-red-500' : ''; ?>" id="numero" name="numero" type="number" placeholder="Ex: 10" value="<?= $dados['numero'] ?? '' ?>">
                <span class="text-red-500 text-xs italic"><?= $dados['numero_err'] ?? '' ?></span>
            </div>
            
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Adicionar Mesa e Gerar QR Code
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/admin/partials/footer.php'; ?>
