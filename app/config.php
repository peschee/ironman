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

$app['config.default_text'] = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';
