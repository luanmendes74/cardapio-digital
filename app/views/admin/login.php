<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Painel de Controle</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Acesso ao Painel</h2>
        
        <form action="/admin/login" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" id="email" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?= (!empty($dados['email_err'])) ? 'border-red-500' : ''; ?>" 
                       value="<?= htmlspecialchars($dados['email']) ?>">
                <?php if(!empty($dados['email_err'])): ?>
                    <p class="text-red-500 text-xs italic mt-2"><?= $dados['email_err'] ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-6">
                <label for="senha" class="block text-gray-700 text-sm font-bold mb-2">Senha</label>
                <input type="password" name="senha" id="senha" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline <?= (!empty($dados['senha_err'])) ? 'border-red-500' : ''; ?>">
                <?php if(!empty($dados['senha_err'])): ?>
                    <p class="text-red-500 text-xs italic"><?= $dados['senha_err'] ?></p>
                <?php endif; ?>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Entrar
                </button>
            </div>
        </form>
    </div>

</body>
</html>