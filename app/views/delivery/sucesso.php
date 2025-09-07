<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Realizado - <?= $estabelecimento->nome ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Pedido Realizado!
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Seu pedido foi enviado com sucesso para <?= htmlspecialchars($estabelecimento->nome) ?>
                </p>
            </div>
            
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detalhes do Pedido</h3>
                    
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>Número do Pedido:</strong> #<?= $pedido->id ?></p>
                        <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido->cliente_nome) ?></p>
                        <p><strong>Telefone:</strong> <?= htmlspecialchars($pedido->cliente_telefone) ?></p>
                        <p><strong>Endereço:</strong> <?= htmlspecialchars($pedido->cliente_endereco) ?></p>
                        <p><strong>Total:</strong> R$ <?= number_format($pedido->total, 2, ',', '.') ?></p>
                        <p><strong>Status:</strong> <span class="text-yellow-600 font-medium">Recebido</span></p>
                    </div>
                    
                    <?php if ($pedido->observacoes): ?>
                        <div class="mt-4 p-3 bg-gray-50 rounded">
                            <p class="text-sm text-gray-700">
                                <strong>Observações:</strong><br>
                                <?= htmlspecialchars($pedido->observacoes) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="mt-6 space-y-3">
                    <a href="<?= URL_BASE ?>/delivery" 
                       class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Fazer Novo Pedido
                    </a>
                    
                    <?php if ($estabelecimento->whatsapp): ?>
                        <a href="https://wa.me/<?= $estabelecimento->whatsapp ?>?text=Olá! Gostaria de falar sobre o pedido #<?= $pedido->id ?>" 
                           target="_blank"
                           class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fab fa-whatsapp mr-2"></i>
                            Falar no WhatsApp
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="text-center">
                <p class="text-xs text-gray-500">
                    Obrigado por escolher <?= htmlspecialchars($estabelecimento->nome) ?>!
                </p>
            </div>
        </div>
    </div>
</body>
</html>


