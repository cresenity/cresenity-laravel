<?php
namespace Cresenity\Laravel;

use Illuminate\Support\Arr;

class CTemporary
{

    /**
     * @param null|mixed $diskName
     *
     * @return CStorage_Adapter
     */
    public static function disk($diskName = null)
    {
        return CStorage::instance()->temp($diskName);
    }

    public static function defaultDiskDriver()
    {
        $defaultDiskName = static::defaultDiskName();
        $config = CF::config('storage.disks.' . $defaultDiskName);

        return Arr::get($config, 'driver');
    }

    public static function defaultDiskName()
    {
        return CF::config('storage.temp');
    }

    /**
     * @param string $path
     *
     * @return \CTemporary_Directory
     */
    public static function createDirectory($path)
    {
        return new CTemporary_Directory($path);
    }

    /**
     * @param string $filename
     *
     * @return \CTemporary_File
     */
    public static function createFile($filename)
    {
        return new CTemporary_File(self::createDirectory(dirname($filename)), basename($filename));
    }

    /**
     * @param null|mixed $folder
     *
     * @return string
     */
    public static function getDirectory($folder = null)
    {
        $path = DOCROOT . 'temp' . DIRECTORY_SEPARATOR;

        if ($folder != null) {
            $path .= $folder . DIRECTORY_SEPARATOR;
        }

        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }

        return $path;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function makeDir($path)
    {
        if (!is_dir($path)) {
            mkdir($path);
        }

        return $path;
    }

    /**
     * @param string $path
     * @param string $folder
     *
     * @return string
     */
    public static function makeFolder($path, $folder)
    {
        $path = $path . $folder . DIRECTORY_SEPARATOR;
        self::makeDir($path);

        return $path;
    }

    /**
     * @param string $folder
     * @param string $filename
     *
     * @return string
     */
    public static function makePath($folder, $filename)
    {
        $depth = 5;
        $mainFolder = substr($filename, 0, 8);
        $path = self::getDirectory();
        $path = self::makeFolder($path, $folder);
        $path = self::makeFolder($path, $mainFolder);
        $basefile = basename($filename);
        for ($i = 0; $i < $depth; $i++) {
            $c = '_';
            if (strlen($basefile) > ($i + 1)) {
                $c = substr($basefile, $i + 8, 1);
                if (strlen($c) == 0) {
                    $c = '_';
                }
                $path = self::makefolder($path, $c);
            }
        }

        return $path . $filename;
    }

    public static function getPath($folder = null, $filename = null)
    {
        if ($folder == null) {
            $folder = 'common';
        }
        if ($filename == null) {
            $filename = date('Ymd') . cutils::randmd5();
        }
        $depth = 5;
        $mainFolder = substr($filename, 0, 8);
        $path = '';
        $path = $path . rtrim($folder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $path = $path . $mainFolder . DIRECTORY_SEPARATOR;

        $basefile = basename($filename);
        for ($i = 0; $i < $depth; $i++) {
            $c = '_';
            if (strlen($basefile) > ($i + 1)) {
                $c = substr($basefile, $i + 8, 1);
                if (strlen($c) == 0) {
                    $c = '_';
                }
                $path = $path = $path . $c . DIRECTORY_SEPARATOR;
            }
        }

        return $path . $filename;
    }

    public static function getLocalPath($folder, $filename = null)
    {
        return rtrim(DOCROOT, '/') . '/temp/' . static::getPath($folder, $filename);
    }

    /**
     * @param string $folder
     * @param string $filename
     *
     * @return string
     */
    public static function getUrl($folder, $filename)
    {
        $path = static::getPath($folder, $filename);

        return static::disk()->url($path);

        $mainFolder = substr($filename, 0, 8);
        $basefile = basename($filename);
        $url = curl::base() . 'temp/' . $folder . '/' . $mainFolder . '/';
        $depth = 5;
        for ($i = 0; $i < $depth; $i++) {
            $c = '_';
            if (strlen($basefile) > ($i + 1)) {
                $c = substr($basefile, $i + 8, 1);
                if (strlen($c) == 0) {
                    $c = '_';
                }
                $url .= $c . '/';
            }
        }

        return $url . $filename;
    }

    /**
     * @param string $folder
     * @param string $filename
     *
     * @return bool
     */
    public static function delete($folder, $filename)
    {
        $disk = static::disk();

        return $disk->delete(static::getPath($folder, $filename));
    }

    /**
     * @param string $folder
     * @param string $filename
     *
     * @return bool
     */
    public static function deleteLocal($folder, $filename)
    {
        $path = static::getLocalPath($folder, $filename);

        return @unlink($path);
    }

    public static function generateRandomFilename($extension = null)
    {
        return date('Ymd') . cutils::randmd5() . (strlen($extension) > 0 ? $extension : '');
    }

    public static function put($folder, $content, $filename = null)
    {
        if ($filename == null) {
            $filename = static::generateRandomFilename();
        }
        $path = static::getPath($folder, $filename);
        static::disk()->put($path, $content);

        return $path;
    }

    public static function get($folder, $filename)
    {
        $path = static::getPath($folder, $filename);

        return static::disk()->get($path);
    }

    public static function getSize($folder, $filename)
    {
        $path = static::getPath($folder, $filename);

        return static::disk()->size($path);
    }

    public static function isExists($folder, $filename)
    {
        $path = static::getPath($folder, $filename);

        return static::disk()->exists($path);
    }

    /**
     * @return CTemporary_Instance
     */
    public static function local()
    {
        return CTemporary::instance('local-temp');
    }

    public static function createLocalFile($content, $folder = null, $suffix = null, $delete = true)
    {
        return new CTemporary_LocalFile($content, $folder, $suffix, $delete);
    }

    /**
     * @param null|mixed $disk
     *
     * @return CTemporary_Instance
     */
    public static function instance($disk = null)
    {
        return CTemporary_Instance::instance($disk);
    }

    public static function __callStatic($name, $arguments)
    {
        return CTemporary::instance()->$name(...$arguments);
    }

    /**
     * @param string $location
     *
     * @return CTemporary_CustomDirectory
     */
    public static function customDirectory($location = '')
    {
        return new CTemporary_CustomDirectory($location);
    }
}
