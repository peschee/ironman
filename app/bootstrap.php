<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

$app = new Application();

require_once __DIR__ . '/config.php';

$app->register(new UrlGeneratorServiceProvider());
$app->register(new ServiceControllerServiceProvider());

return $app;
