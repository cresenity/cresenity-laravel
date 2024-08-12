<?php
namespace Cresenity\Laravel;

use Cresenity\Laravel\CAjax\Info;
use Cresenity\Laravel\CAjax\Method;
use Cresenity\Laravel\CBase\ForwarderStaticClass;

class CAjax
{
    const TYPE_SELECT_SEARCH = 'SelectSearch';

    const TYPE_CALLBACK = 'Callback';

    const TYPE_DATA_TABLE = 'DataTable';

    const TYPE_FILE_MANAGER = 'FileManager';

    const TYPE_IMG_UPLOAD = 'ImgUpload';

    const TYPE_FILE_UPLOAD = 'FileUpload';

    const TYPE_RELOAD = 'Reload';

    const TYPE_VALIDATION = 'Validation';

    /**
     * @param null|array|string $options
     *
     * @return \Cresenity\Laravel\CAjax\Method
     */
    public static function createMethod($options = null)
    {
        if (!is_array($options)) {
            if ($options != null) {
                return Method::createFromJson($options);
            }
        }

        return new Method($options);
    }

    public static function getData($file)
    {
        $filename = $file . '.tmp';

        $file = CTemporary::getPath('ajax', $filename);

        $disk = CTemporary::disk();
        if (!$disk->exists($file)) {
            throw new \Exception(\c::__('failed to get temporary file :filename', [':filename' => $file]));
        }
        $json = $disk->get($file);

        $data = json_decode($json, true);

        return $data;
    }

    public static function setData($file, $data)
    {
        $filename = $file . '.tmp';

        $file = CTemporary::getPath('ajax', $filename);

        $disk = CTemporary::disk();

        $disk->put($file, json_encode($data));

        return $data;
    }

    public static function getDefaultExpiration()
    {
        return \c::now()->addMinutes(CF::config('cresenity.ajax.expiration', 60))->getTimestamp();
    }

    /**
     * @return \Cresenity\Laravel\CAjax\Info|ForwarderStaticClass
     */
    public static function info()
    {
        return new ForwarderStaticClass(Info::class);
    }
}
