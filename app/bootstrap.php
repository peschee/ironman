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

require_once __DIR__ . DIRECTORY_SEPARATOR . $config;


$app->register(new UrlGeneratorServiceProvider());
$app->register(new ServiceControllerServiceProvider());

return $app;
