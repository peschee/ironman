<?php

namespace Ironman;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;
use Imagine\Image\Point\Center;

/**
 * Artwork class for generating images.
 *
 * @package Ironman
 */
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
     * @param int $size Default dimensions (size will be used for both, width + height)
     * @param bool $useTimestamp If false, only the md5 sum of the text is going to be use for filename generation
     * @param string $fileType The filetype to generate (gif, png, jpg)
     * @throws \RuntimeException
     * @return string The full path to the generated image
     */
    public function generateAndSaveImage($path, $size = 400, $useTimestamp = true, $fileType = 'gif') {
        if (!is_dir($path) || !is_writable($path)) {
            throw new \RuntimeException(sprintf('Path "%s" either does not exist or is not writable.', $path));
        }

        $count = $this->countWordLetters($this->text);
        $normalized = $this->normalizeArray($count);

        $top = array_slice($normalized, 0, 1, true);
        $topOther = array_slice($normalized, 1, 6, true);

        // nice flat UI colors
        // @see http://flatuicolors.com/
        $colors = array(
            'grays' => array(
                '#ecf0f1',
                '#bdc3c7',
                '#95a5a6',
                '#7f8c8d',
                '#2c3e50',
                '#34495e',
                '#183047',
            ),

            'greens' => array(
                '#2ecc71',
                '#27ae60',
                '#1abc9c',
                '#16a085',
                '#bdc3c7',
                '#95a5a6',
                '#7f8c8d',
            ),

            'reds' => array(
                '#f1c40f',
                '#e67e22',
                '#d35400',
                '#e74c3c',
                '#c0392b',
                '#ecf0f1',
                '#bdc3c7',
            ),
        );

        $width = $height = $size;

        $filename = ($useTimestamp ? md5($this->created . $this->text) : md5($this->text)) . '.' . $fileType;

        // create a new image with a white background
        $imagine = new Imagine();
        $imageBox = new Box($width, $height);
        $imageCenter = new Center($imageBox);
        $image = $imagine->create($imageBox, new Color('fff'));

        $selectedColors = $colors[array_rand($colors)];

        // draw the initial circle for the word with the highest count
        $image
          ->draw()
          ->ellipse($imageCenter, $imageBox->scale(0.99), new Color(array_shift($selectedColors)), true);

        foreach ($topOther as $count) {
            if ($count === 0) {
                $count = 0.01;
            }

            $image
              ->draw()
              ->ellipse($imageCenter, $imageBox->scale($count), new Color(array_shift($selectedColors)), true);
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

    /**
     * Normalizes an array (xi - xmin) / (xmax - xmin)
     *
     * @param $array Array to normalize
     * @return array Array with normalized values (between 0 and 1)
     */
    protected function normalizeArray($array) {
        $min = min($array);
        $max = max($array);
        $div = ($max - $min) === 0 ? 1 : $max - $min;

        $normalized = array();

        foreach ($array as $key => $value) {
            $normalized[$key] = ($value - $min) / $div;
        }

        return $normalized;
    }

}
