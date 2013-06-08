<?php

define('ARTWORK_WEB_DIR', '/img/generated');
define('WEB_PATH', __DIR__);
define('PAGODA_SHARED_PATH', '/home/mfd-ironman/shared');

require_once __DIR__ . '/../vendor/autoload.php';

use Ironman\Artwork;

$text = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';

try {
    $targetDir = (($_SERVER['ENV'] === 'production') ? PAGODA_SHARED_PATH : WEB_PATH) . ARTWORK_WEB_DIR;

    $artwork = new Artwork($text);
    $image = $artwork->generateAndSaveImage($targetDir);

    $imageLink = 'http://' . $_SERVER['HTTP_HOST'] . ARTWORK_WEB_DIR . '/'. $image;
} catch (\Exception $e) {
    die($e->getMessage());
}

printf('<p>Image <a href="%s">%s</a> has been generated</p>', $imageLink, $image);
