<?php

$app['debug'] = true;

$app['config.artwork_web_dir'] = '/img/generated';
$app['config.web_path'] = __DIR__ . '/../web';
$app['config.pagoda_shared_path'] = '/var/www/web';

if (isset($_SERVER['ENV']) && $_SERVER['ENV'] === 'production') {
    $app['config.target_images_dir'] = $app['config.pagoda_shared_path'];
} else {
    $app['config.target_images_dir'] = $app['config.web_path'];
}

$app['config.target_images_dir'] .= $app['config.artwork_web_dir'];
