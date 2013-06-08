<?php

define('ARTWORK_WEB_DIR', '/img/generated');
define('WEB_PATH', __DIR__);

require_once __DIR__ . '/../vendor/autoload.php';

use Ironman\Artwork;

$text = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';

try {
    $artwork = new Artwork($text);
    $image = $artwork->generateAndSaveImage(WEB_PATH . ARTWORK_WEB_DIR);

    $imageLink = 'http://' . $_SERVER['HTTP_HOST'] . ARTWORK_WEB_DIR . '/'. $image;
} catch (\Exception $e) {
    die($e->getMessage());
}

printf('<p>Image <a href="%s">%s</a> has been generated</p>', $imageLink, $image);

