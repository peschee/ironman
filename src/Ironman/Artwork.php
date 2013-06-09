<?php

namespace Ironman;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;
use Imagine\Image\Point\Center;

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
     * @param $path string The path to save the image to
     * @param bool $useTimestamp If false, only the md5 sum of the text is going to be use for filename generation
     * @throws \RuntimeException
     * @return string The full path to the generated image
     */
    public function generateAndSaveImage($path, $useTimestamp = true, $fileType = 'gif') {
        if (!is_dir($path) || !is_writable($path)) {
            throw new \RuntimeException(sprintf('Path "%s" either does not exist or is not writable.', $path));
        }

        $count = $this->countWordLetters($this->text);
        $top = array_slice($count, 0, 1, true);
        $topFour = array_slice($count, 1, 4, true);
        $topFourSum = array_sum($topFour);

        // nice flat UI colors
        // @see http://flatuicolors.com/
        $colors = array(
            '#f1c40f',
            '#e67e22',
            '#d35400',
            '#e74c3c',
            '#c0392b'
        );

        $width = $height = 400;

        $filename = ($useTimestamp ? md5($this->created . $this->text) : md5($this->text)) . '.' . $fileType;

        // create a new image with a white background
        $imagine = new Imagine();
        $imageBox = new Box($width, $height);
        $imageCenter = new Center($imageBox);
        $image = $imagine->create($imageBox, new Color('fff'));

        // draw the initial circle for the word with the highest count
        $image
          ->draw()
          ->ellipse($imageCenter, $imageBox->scale(0.999), new Color(array_shift($colors)), true);

        foreach ($topFour as $letters => $count) {
            $percentage = 100 / $topFourSum * $count;
            $percentage -= 0.001;

            $image
              ->draw()
              ->ellipse($imageCenter, $imageBox->scale($percentage / 100), new Color(array_shift($colors)), true);
        }

        $image->save($path . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    /**
     * Counts the occurences of word lengths in a string.
     *
     * @param $text string Text to parse.
     * @return array A sorted (hi to low) array with each word length as key and the number of its occurences as value.
     */
    protected function countWordLetters($text) {
        $words = explode(' ', trim($text));
        $count = array();

        foreach ($words as $word) {
            $len = strlen($word);

            if (!isset($count[$len])) {
                $count[$len] = 0;
            }

            $count[$len]++;
        }

        arsort($count);

        return $count;
    }

}
