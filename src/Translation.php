<?php
namespace Cresenity\Laravel;

use Illuminate\Translation\Translator;

class Translation
{
    protected static $translator;

    public static function translator($locale = null)
    {
        if (!is_array(static::$translator)) {
            static::$translator = [];
        }

        return new Translator(self::getFileLoader(), $locale);
        if ($locale == null) {
            $locale = app()->getLocale();
            if (!isset(static::$translator[$locale])) {
                static::$translator[$locale] = \c::container('translator');
            }
        }

        if (!isset(static::$translator[$locale])) {
            static::$translator[$locale] = new Translator(self::getFileLoader(), $locale);
        }

        return static::$translator[$locale];
    }

    public static function getFileLoader()
    {
        return \c::container('translation.loader');
    }
}