<?php
declare(strict_types = 1);
namespace DataTypes\Helper;

use DataTypes\Exception\InvalidKeyException;

class IdHelper
{
    public static function formatId($id, bool $allowSlash = false, int $maxLength = 0):string
    {
        //TODO: only allow int?
        if (!is_string($id) && !is_int($id)) {
            if (is_array($id)) {
                throw new InvalidKeyException('Id must be int or string, `' . self::getObjectType($id) . '` given (' . json_encode($id) . ') (' . json_encode(debug_backtrace()) . ')');
            }

            throw new InvalidKeyException('Id must be int or string, `' . self::getObjectType($id) . '` given');
        }

        // $id = iconv("UTF-8", "UTF-8//IGNORE", $id);
        //$id = mb_convert_encoding($id, 'UTF-8', 'UTF-8');
        //$id = mb_strtolower($id);
        $id = str_replace([
            'ë',
            'é',
            'è',
            'ç',
            '�',
        ], [
            'e',
            'e',
            'e',
            'c',
            '_',

        ], $id);
        //Remove special characters (http://regexr.com/3cpha)
        //Don't use \w as character groop, it will allow special non utf-8 characters
        $regex = '/([^a-z0-9]+)|(\_{2,})/mi';
        if ($allowSlash) {
            $regex = '/([^a-z0-9\/]+)|(\s{2,})|(\_{2,})|(\/{2,})/im';
        }

        //        if ($allowWhiteSpace) {
        //            $regex = '/([^\w\s]+)|(\s{2,})|(\_{2,})/im';
        //            if ($allowSlash) {
        //                $regex = '/([^\w\s\/]+)|(\s{2,})|(\_{2,})|(\/{2,})/im';
        //            }
        //        }

        $id = preg_replace($regex, '_', $id);

        if (strlen($id) < 1) {
            $ex = new InvalidKeyException('Id must be longer than 1 character');
            echo $ex->getTraceAsString();
            throw $ex;
        }

        $id = strtolower($id);

        if ($maxLength > 0) {
            $id = substr($id, 0, $maxLength);
        }

        //Remove leading or trailing slashes
        $id = trim($id, '_');
        //Remove multi underscores
        //        $code = preg_replace('!\s+!', ' ', $code);
        //        $code = trim($code);
        //        $code = str_replace(' ', '_', $code);

        return $id;
    }

    private static function getObjectType($var):string
    {
        $type = gettype($var);
        if ($type === 'object') {
            $type = get_class($var);
        }

        return $type;
    }
}
