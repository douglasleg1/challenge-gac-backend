<?php

namespace App\Helpers;

use Exception;

class ValidateErrors
{
    public static function toStr($errors)
    {
        try {
            $errorsStr = '';
            foreach ($errors->getMessages() as $key => $value) {
                if ($errorsStr == '') {
                    $errorsStr .= "$key: " . $value[0];
                    continue;
                }
                $errorsStr .=  " | " . "$key: " . $value[0];
            }
            return $errorsStr;
        } catch (Exception $error) {
            throw new Exception($error);
        }
    }
}