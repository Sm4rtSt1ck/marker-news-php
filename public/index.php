<?php

session_start();

require_once __DIR__ . '/../app/helpers/db.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$routes = require __DIR__ . '/../config/routes.php';

if (array_key_exists($requestUri, $routes)) {
    $file = __DIR__ . $routes[$requestUri];
    if (file_exists($file)) {
        include $file;
    } else {
        http_response_code(404);
        echo "Страница не найдена (файл маршрута не существует).";
    }
} else {
    http_response_code(404);
    echo "Страница не найдена (маршрут не определен).";
}
