<?php
namespace Cresenity\Laravel\CAjax;

use Cresenity\Laravel\CAjax\Engine\FileUpload;
use Cresenity\Laravel\CTemporary;

class Info
{
    public static function getFileInfo($fileId)
    {
        $path = CTemporary::getPath(FileUpload::FOLDER_INFO, $fileId);
        $info = CTemporary::disk()->get($path);

        return json_decode($info, true);
    }

    public static function getImageInfo($fileId)
    {
        $path = CTemporary::getPath(CAjax_Engine_ImgUpload::FOLDER_INFO, $fileId);
        $info = CTemporary::disk()->get($path);

        return json_decode($info, true);
    }
}
