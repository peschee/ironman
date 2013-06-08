<?php

namespace Ironman;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;

class Artwork {

    protected $text;
    protected $created;

    public function __construct($text)
    {
        $this->text = $text;
        $this->created = time();
    }

    /**
     * Generates and saves the artwork image.
     *
     * @param $path
     * @throws \RuntimeException
     * @return string The full path to the generated image.
     */
    public function generateAndSaveImage($path) {
        if (!is_dir($path) || !is_writable($path)) {
            throw new \RuntimeException(sprintf('Path "%s" either does not exist or is not writable.', $path));
        }

        $filename = md5($this->created . $this->text) . '.gif';

        $imagine = new Imagine();
        $image = $imagine->create(new Box(400, 300), new Color('#000'));

        $image
            ->draw()
            ->ellipse(new Point(200, 150), new Box(300, 225), new Color('fff'));

        $image->save($path . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

}
