<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

$app = new Application();

if (isset($_SERVER['ENV']) && $_SERVER['ENV'] === 'production') {
    $config = 'config.prod.php';
} else {
    $config = 'config.php';
}

if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . $config)) {
    $app->abort(500, sprintf('No configuration file %s found. Create one based on config.php.dist.', $config));
}

require_once __DIR__ . DIRECTORY_SEPARATOR . $config;


$app->register(new UrlGeneratorServiceProvider());
$app->register(new ServiceControllerServiceProvider());

return $app;
