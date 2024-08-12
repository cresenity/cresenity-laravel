<?php
namespace Cresenity\Laravel\CAjax\Engine;

use Cresenity\Laravel\CAjax\Engine;
use Cresenity\Laravel\CF;
use Cresenity\Laravel\CTemporary;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FileUpload extends Engine
{
    const FOLDER = 'fileupload';

    const FOLDER_INFO = 'fileuploadinfo';

    public function execute()
    {
        $data = $this->ajaxMethod->getData();
        $inputName = Arr::get($data, 'inputName');
        $allowedExtension = Arr::get($data, 'allowedExtension', []);
        $validationCallback = Arr::get($data, 'validationCallback');
        $withInfo = Arr::get($data, 'withInfo', false);
        $diskName = Arr::get($data, 'disk', CF::config('cresenity.storage.temp'));
        $fileId = '';
        $fileName = '';
        if (isset($_FILES[$inputName], $_FILES[$inputName]['name'])) {
            for ($i = 0; $i < count($_FILES[$inputName]['name']); $i++) {
                $fileName = $_FILES[$inputName]['name'][$i];
                //$fileSize = $_FILES[$inputName]['size'][$i];
                $ext = pathinfo($_FILES[$inputName]['name'][$i], PATHINFO_EXTENSION);
                if (in_array($ext, ['php', 'sh', 'htm', 'pht'])) {
                    die('Not Allowed X_X');
                }
                if (Str::startsWith($ext, ['php', 'sh', 'htm', 'pht'])) {
                    die('Not Allowed X_X');
                }

                if ($allowedExtension) {
                    if (!in_array(strtolower($ext), $allowedExtension)) {
                        die('Not Allowed X_X');
                    }
                }
                if ($validationCallback && $validationCallback instanceof \Opis\Closure\SerializableClosure) {
                    $validationCallback->__invoke($_FILES[$inputName]['name'][$i], $_FILES[$inputName]['tmp_name'][$i]);
                }

                $extension = '.' . $ext;

                $fileId = date('Ymd') . cutils::randmd5() . $extension;
                $disk = CTemporary::disk();
                $fullfilename = CTemporary::getPath('fileupload', $fileId);

                if (!$disk->put($fullfilename, file_get_contents($_FILES[$inputName]['tmp_name'][$i]))) {
                    die('fail upload from ' . $_FILES[$inputName]['tmp_name'][$i] . ' to ' . $fullfilename);
                }

                if ($withInfo) {
                    $infoData['filename'] = $fileName;
                    $infoData['fileId'] = $fileId;
                    $infoData['temporaryPath'] = $fullfilename;
                    $infoData['temporaryDisk'] = $diskName;
                    $infoData['url'] = CTemporary::getUrl(static::FOLDER, $fileId);
                    $fullfilenameinf = CTemporary::put(static::FOLDER_INFO, json_encode($infoData), $fileId);
                }
                $return[] = $fileId;
            }
        }

        if (isset($_POST[$inputName])) {
            $fileDataArray = $_POST[$inputName];
            $filenameArray = $_POST[$inputName . '_filename'];

            if (!is_array($fileDataArray)) {
                $fileDataArray = [$fileDataArray];
            }
            if (!is_array($filenameArray)) {
                $filenameArray = [$filenameArray];
            }
            foreach ($fileDataArray as $k => $fileData) {
                $fileName = Arr::get($filenameArray, $k);

                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                if (in_array($ext, ['php', 'sh', 'htm', 'pht'])) {
                    die('Not Allowed X_X');
                }

                if (Str::startsWith($ext, ['php', 'sh', 'htm', 'pht'])) {
                    die('Not Allowed X_X');
                }

                if ($allowedExtension) {
                    if (!in_array(strtolower($ext), $allowedExtension)) {
                        die('Not Allowed X_X');
                    }
                }
                if ($validationCallback && $validationCallback instanceof \Opis\Closure\SerializableClosure) {
                    $validationCallback->__invoke($fileName, $fileData);
                }

                $extension = '.' . $ext;

                $filteredData = substr($fileData, strpos($fileData, ',') + 1);
                $unencodedData = base64_decode($filteredData);
                $fileId = date('Ymd') . cutils::randmd5() . $extension;
                $fullfilename = CTemporary::getPath(static::FOLDER, $fileId);
                $disk = CTemporary::disk();
                $disk->put($fullfilename, $unencodedData);
                if ($withInfo) {
                    $infoData['filename'] = $fileName;
                    $infoData['fileId'] = $fileId;
                    $infoData['temporaryPath'] = $fullfilename;
                    $infoData['temporaryDisk'] = $diskName;
                    $infoData['url'] = CTemporary::getUrl(static::FOLDER, $fileId);
                    $fullfilenameinf = CTemporary::put(static::FOLDER_INFO, json_encode($infoData), $fileId);
                }
                $return[] = $fileId;
            }
        }
        $return = [
            'fileId' => $fileId,
            'fileName' => $fileName,
            'url' => CTemporary::getUrl(static::FOLDER, $fileId),
        ];

        return json_encode($return);
    }
}
