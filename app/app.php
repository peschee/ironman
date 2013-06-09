<?php

use Ironman\Artwork;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/** @var \Silex\Application $app */
$app = require_once __DIR__ . '/bootstrap.php';

$app->get('/', function () use ($app) {

    $text = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';

    $artwork = new Artwork($text);
    $image = $artwork->generateAndSaveImage($app['config.target_images_dir']);

    $imageLink = 'http://' . $_SERVER['HTTP_HOST'] . $app['config.artwork_web_dir'] . '/'. $image;

    return new Response(sprintf('<p>Image <a href="%s">%s</a> has been generated</p>', $imageLink, $image));
})->bind('homepage');

$app->error(function(\Exception $e, $code) use ($app) {

    if ($app['debug']) {
        return;
    }

        echo '<pre>';
        var_dump($app['debug']);
        echo '<pre>';



    $message = 'We are very sorry, something went wrong.';

    if ($e instanceof NotFoundHttpException) {
        $message = 'The requested page could not be found';
    }

    $code = ($e instanceof HttpException) ? $e->getStatusCode() : 500;

    return new Response($message, $code);
});

return $app;
