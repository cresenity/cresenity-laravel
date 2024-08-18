<?php

namespace Cresenity\Laravel;

use Cresenity\Laravel\CImage\Avatar;

class CImage
{
    /**
     * Create CImage_Avatar Object.
     *
     * @param string $engineName
     *
     * @return Avatar
     */
    public static function avatar($engineName = 'Initials')
    {
        return new Avatar($engineName);
    }

    /**
     * @param string $pathToImage
     *
     * @return CImage_Image
     */
    public static function image($pathToImage)
    {
        return CImage_Image::load($pathToImage);
    }

    /**
     * @param int $width
     * @param int $height
     *
     * @return CImage_Chart_Builder
     */
    public static function chart($width = 500, $height = 200)
    {
        return new CImage_Chart_Builder($width, $height);
    }
}
