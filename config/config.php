<?php

$config = [
    'db_host' => 'localhost',
    'db_name' => 'marker_news',
    'db_user' => 'root',
    'db_pass' => '',
];

$localConfigFile = __DIR__ . '/local.php';
if (file_exists($localConfigFile)) {
    $localConfig = require $localConfigFile;
    $config = array_merge($config, $localConfig);
}

return $config;
