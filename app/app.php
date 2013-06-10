<?php

use Ironman\Artwork;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/** @var \Silex\Application $app */
$app = require_once __DIR__ . '/bootstrap.php';

/**
 * Homepage route
 */
$app->get('/', function () use ($app) {

    $artwork = new Artwork($app['config.default_text']);
    $imageFile = $artwork->generateAndSaveImage($app['config.target_images_dir']);

    $imageLink = 'http://' . $_SERVER['HTTP_HOST'] . $app['config.artwork_web_dir'] . '/'. $imageFile;

    return new Response(sprintf('<p>Image <a href="%s">%s</a> has been generated</p>', $imageLink, $imageFile));
})->bind('homepage');

/**
 * Generate image from parameters
 */
$app->get('/generate/{text}', function ($text) use ($app) {

    $artwork = new Artwork($text);
    $imageFile = $artwork->generateAndSaveImage($app['config.target_images_dir'], false, 'png');

    return new Response(sprintf('<img src="%s" />', $app['config.artwork_web_dir'] . '/'. $imageFile));
})
->bind('image_generate')
->value('text', $app['config.default_text']);

/**
 * Post method to generate images from a 'text' json parameter
 */
$app->post('/generate', function (Request $request)  use ($app) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());

        if ($text = $request->get('text')) {
            $artwork = new Artwork($text);
            $imageFile = $artwork->generateAndSaveImage($app['config.target_images_dir'], false, 'png');

            if (!$request->get('rfa_id')) {
                return $app->json(array(), 201, array(
                    'Location' => $request->getSchemeAndHttpHost() . $request->getBasePath() . $app['config.artwork_web_dir'] . '/'. $imageFile
                ));
            }

            // redirect request back to MFD
            
        }
    }
});

$app->error(function(\Exception $e, $code) use ($app) {

    if ($app['debug']) {
        return;
    }

    $message = 'We are very sorry, something went wrong.';

    if ($e instanceof NotFoundHttpException) {
        $message = 'The requested page could not be found';
    }

    $code = ($e instanceof HttpException) ? $e->getStatusCode() : 500;

    return new Response($message, $code);
});

return $app;
