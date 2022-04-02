<?php

namespace app\components;

use Yii;
use DateTime;
use DateTimeZone;
use yii\web\Response;
use app\models\Logger;
use app\models\Options;
use app\models\CompanyProfile;

class Utils
{
    /**
     * handles json responses
     *
     * @param $data
     * @param int $statusCode
     * @return mixed
     */

    const IMAGE_ORIGINAL = 'original';
    const IMAGE_LARGE = 'large';
    const IMAGE_MEDIUM = 'medium';
    const IMAGE_SMALL = 'small';
    const IMAGE_THUMB = 'thumb';
    const IMAGE_LOW = 'low';

    public static function alias($name)
    {
        return Yii::getAlias($name);
    }

    /**
     * a shorthand method to get params value
     *
     * @param $key
     * @return mixed
     */
    public static function get($key)
    {
        if (!$key) {
            return false;
        }
        if (!isset(Yii::$app->params[$key])) {
            return false;
        }

        return Yii::$app->params[$key];
    }

    /**
     * @param $filename
     * @return bool
     */
    public static function isFile($filename)
    {
        return is_file($filename);
    }

    /**
     * @param $dirname
     * @return bool
     */
    public static function isDir($dirname)
    {
        return is_dir($dirname);
    }

    /**
     * @param $filename
     * @return bool
     */
    public static function exits($filename)
    {
        return file_exists($filename);
    }

    /**
     * @param $var
     * @param bool $flag
     */
    public static function debug($var, $flag = true)
    {
        echo '<pre>';
        if ($flag) {
            print_r($var);
        } else {
            var_dump($var);
        }
        echo '</pre>';
        die();
    }

    public static function dump($var)
    {
        \yii\helpers\VarDumper::dump($var);
        die();
    }

    public static function getUploadPath()
    {
        return self::alias('@uploads');
    }

    public static function getAllowedTypes()
    {
        $uploadOptions = self::get('uploadOptions');

        return $uploadOptions['allowedTypes'];
    }

    public static function getAllowedImageTypes()
    {
        $uploadOptions = self::get('uploadOptions');
        $imageExt = $uploadOptions['imageTypes'];
        $imageTypes = $uploadOptions['allowedTypes'];
        foreach ($imageTypes as $key => $type) {
            if (!in_array($key, $imageExt)) {
                unset($imageTypes[$key]);
            }
        }

        return $imageTypes;
    }

    /**
     * @return mixed
     */
    public static function getMaxUploadCount()
    {
        $uploadOptions = self::get('uploadOptions');
        return $uploadOptions['maxFileCount'];
    }

    /**
     * @return mixed
     */
    public static function getMaxUploadSize()
    {
        $uploadOptions = self::get('uploadOptions');
        return $uploadOptions['maxFileSize'];
    }

    /**
     * @param $file
     * @param string $type
     * @return bool
     */
    public static function validateFile($file, $type = 'file')
    {
        if (!$file || !self::exits($file->tempName)) {
            return false;
        }

        $maxSize = self::getMaxUploadSize();
        if ($file->size > $maxSize) {
            return false;
        }

        $uploadOptions = self::get('uploadOptions');
        $key = 'allowed' . ucfirst($type) . 'Types';
        $allowedTypes = $uploadOptions[$key];

        switch ($type) {
            case "image":
                return self::validateImageFile($file, $allowedTypes);
                break;
            case "file":
                return self::validateFileTypes($file, $allowedTypes);
                break;
            default:
                return false;
                break;
        }

        // return false;
    }

    /**
     * @param $file
     * @param $allowedTypes
     * @return bool
     */
    protected static function validateImageFile($file, $allowedTypes)
    {
        //$isValid = self::validateFileTypes($file, $allowedTypes);
        $isValid = true;
        //if( $isValid ) {
        $ext = $file->extension;
        $fileInfo = getimagesize($file->tempName);
        if (!isset($fileInfo[0]) || !is_numeric($fileInfo[0]) || $fileInfo[0] <= 0
            || !isset($fileInfo[0]) || !is_numeric($fileInfo[0]) || $fileInfo[0] <= 0
            || $fileInfo['mime'] !== $allowedTypes[$ext]) {
            $isValid = false;
        }
        //}
        return $isValid;
    }

    /**
     * @param $file
     * @param $allowedTypes
     * @return bool
     */
    protected static function validateFileTypes($file, $allowedTypes)
    {
        $isValid = true;
        // $ext = end((explode('.', $file->name)));
        $ext = $file->extension;

        if ($file->error !== UPLOAD_ERR_OK
            || !isset($allowedTypes[$ext])
            || $file->type !== $allowedTypes[$ext]) {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * @param $name
     * @param $size
     * @return string
     */
    public static function imageLink($name, $size)
    {
        $base = self::get('baseImageUrl');

        return $base . $size . '/' . $name;
    }

    /**
     * @param $path
     */
    public static function checkDir($path)
    {
        if (is_array($path)) {
            foreach ($path as $p) {
                self::checkDir($p);
            }
        } else {
            // check if upload dir exists
            if (!file_exists($path)) {
                mkdir($path, 0755);
            }
        }
    }

    /**
     * @param $path
     */
    public static function checkFile($path)
    {
        return file_exists($path);
    }

    public static function move($source, $destination)
    {
        if (!self::exits($source)) {
            return false;
        }
        return rename($source, $destination);
    }

    /**
     * @return string
     */
    public static function getRandomName()
    {
        return Yii::$app->security->generateRandomString();
    }

    public static function getApiUrl($endPoint)
    {
        $apiURL = Options::get('api_URL');
        $apiVersion = Options::get('api_version');
        return $apiURL . '/' . $apiVersion . '/' . $endPoint;
    }

    public static function base64_url_encode($input)
    {
        return strtr(base64_encode($input), '+/=', '-_,');
    }

    public static function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_,', '+/='));
    }

    /**
     * returns date &| time in given format
     *
     * @param $format
     * @param null $time
     * @return string
     */
    public static function date($format, $time = null)
    {
        if ($time == null) {
            $time = "now";
        }
        if (!$format || !is_string($format)) {
            $format = 'Y-m-d';
        }
        $datetime = new DateTime($time);
        return $datetime->format($format);
    }

    /**
     * return sql date time
     * @param null $time
     * @return string
     */
    public static function sqlDatetime($time = null)
    {
        return self::date('Y-m-d H:i:s', $time);
    }

    /**
     * return sql date time
     * @param null $time
     * @return string
     */
    public static function humanDate($time = null)
    {
        return self::date('F d, Y', $time);
    }

    /**
     * convert to local time aka BD time
     * @param $originalDateTime
     * @param string $localTimeZone
     */
    public static function localDatetime($originalDateTime, $localTimeZone = 'Asia/Dhaka')
    {
        $systemTimeZone = 'UTC';
        $originalTimeZone = new DateTimeZone($systemTimeZone);

        $datetime = new DateTime($originalDateTime, $originalTimeZone);

        $targetTimeZone = new DateTimeZone($localTimeZone);
        $datetime->setTimeZone($targetTimeZone);

        $format = 'Y-m-d h:i:s a';
        return $datetime->format($format);;
    }

    /**
     * converts array to object
     *
     * @param $arr
     * @return mixed
     */
    public static function toObject($arr)
    {
        return json_decode(json_encode($arr));
    }

    /**
     * logs error message
     *
     * @param $var
     */
    public static function log($var)
    {
        $msg = print_r($var, true);
        Yii::info($msg);
    }

    public static function unlink($file)
    {
        if (self::checkFile($file)) {
            return unlink($file);
        }
        return false;
    }

    /*
     * transfer, package & tour code generator
     * @param $str
     * @return mixed
     */

    public static function tpt_code($str = '')
    {
        return CompanyProfile::findOne(1)->shortName . Yii::$app->user->id . $str . Utils::date('YmdHis');
    }

    public static function encode($var)
    {
        return json_encode($var);
    }

    public static function decode($var)
    {
        return json_decode($var);
    }

    protected static function isUnique($unique, $modelName, $attributeName)
    {
        if ($modelName::findOne([$attributeName => $unique])) {
            $model = new $modelName();
            $unique = CompanyProfile::findOne(1)->shortName . '_TR_' . self::date('Ymd_his') . rand(11, 99) . Yii::$app->user->id;
            return self::isUnique($unique, $modelName, $attributeName);
        }
        return $unique;
    }

    public static function processErrorMessages($errors)
    {
        if (is_array($errors)) {
            $alertMessages = [];
            foreach ($errors as $key => $error) {
                $alertMessages = array_merge($alertMessages, $error);
            }
            return implode(',', $alertMessages);
        } else {
            return $errors;
        }

    }
}