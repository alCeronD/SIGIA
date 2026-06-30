<?php

define('DB_USER', $_ENV['DB_USERNAME']);
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_DATABASE']);
define('DB_PASS', $_ENV['DB_PASSWORD']);
define('CHARSET', 'utf8mb4');
define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN));
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
