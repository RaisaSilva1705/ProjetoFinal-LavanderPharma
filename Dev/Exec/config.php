<?php
// Nome da Farmácia
define('NOME', 'LavanderPharma');

// Caminho do diretório Dev (ajuste conforme sua estrutura de pastas)
define('DEV_PATH', dirname(__DIR__). '/');

// Caminho do diretório Sistema
define('SISTEMA_PATH', dirname(DEV_PATH) . '/Sistema/');

// URL base (para links e redirecionamentos)
define('BASE_URL', 'http://localhost/htdocs/Farmácia/');
define('SISTEMA_URL', BASE_URL . 'Sistema/');
define('DEV_URL', BASE_URL . 'Dev/');

// Credenciais do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'lavanderpharma');
define('DB_USER', 'root');
define('DB_PASS', '1705');

// Timezone (opcional mas recomendado)
date_default_timezone_set('America/Sao_Paulo');
?>