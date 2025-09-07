<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $dados['titulo'] ?? 'Super Admin' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-100">
<div class="flex h-screen bg-gray-200">
    <div class="w-64 bg-gray-800 text-white flex flex-col">
        <div class="text-center py-4 text-xl font-bold border-b border-gray-700">Super Admin</div>
        <nav class="flex-1 px-2 py-4 space-y-2">
            <a href="<?= URL_BASE ?>/SuperAdmin" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                <i class="fas fa-store mr-3"></i> Estabelecimentos
            </a>
            <a href="<?= URL_BASE ?>/SuperAdmin/planos" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                <i class="fas fa-credit-card mr-3"></i> Planos de Assinatura
            </a>
             <a href="<?= URL_BASE ?>/SuperAdmin/configuracoes" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                <i class="fas fa-cogs mr-3"></i> Configurações
            </a>
        </nav>
        <div class="p-4 border-t border-gray-700">
            <a href="<?= URL_BASE ?>/SuperAdmin/logout" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                <i class="fas fa-sign-out-alt mr-3"></i> Sair
            </a>
        </div>
    </div>
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 p-10">

