<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $dados['titulo'] ?? 'Painel Admin' ?> | Cardápio SaaS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1f2937',
                        secondary: '#374151',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal">

<!-- Top Bar -->
<div class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <?php if (!empty($dados['estabelecimento']->logo)): ?>
                <img src="<?= URL_BASE ?>/uploads/<?= htmlspecialchars($dados['estabelecimento']->logo) ?>" alt="Logótipo" class="w-10 h-10 rounded-lg object-cover">
            <?php else: ?>
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-store text-gray-600"></i>
                </div>
            <?php endif; ?>
            <div>
                <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($dados['estabelecimento']->nome ?? 'Painel Admin') ?></h1>
                <p class="text-sm text-gray-500">Painel Administrativo</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-500">
                <i class="fas fa-clock mr-1"></i>
                <span id="current-time"></span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center">
                    <i class="fas fa-user text-white text-xs"></i>
                </div>
                <div class="text-xs">
                    <p class="font-medium text-gray-900"><?= htmlspecialchars($_SESSION['user_nome'] ?? 'Usuário') ?></p>
                    <p class="text-gray-500">Admin</p>
                </div>
                <a href="<?= URL_BASE ?>/admin/logout" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Status do Sistema -->
    <div class="bg-green-50 border-b border-green-200 px-6 py-2">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 text-sm mr-2"></i>
            <div>
                <p class="text-xs font-medium text-green-800">Sistema Online</p>
                <p class="text-xs text-green-600">Funcionando normalmente</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<main class="p-6">