<?php
// Carrega o ficheiro de configuração da base de dados
require_once '../config/database.php';

// Carrega o ficheiro de configuração principal
require_once '../app/config/config.php';

// Carrega os helpers
require_once 'helpers/session_helper.php';
require_once 'helpers/qrcode_helper.php';
require_once 'helpers/url_helper.php';
require_once 'helpers/upload_helper.php';

// --- AUTOLOAD APRIMORADO DE CLASSES ---
spl_autoload_register(function ($className) {
    $directories = [
        '../app/core/',
        '../app/controllers/',
        '../app/models/'
    ];
    foreach ($directories as $dir) {
        $file = $dir . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

