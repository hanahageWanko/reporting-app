<?php
class Validate
{
    public static function resultMessage($success, $status, $message, $extra = [])
    {
        return array_merge([
            'success' => $success,
            'status' => $status,
            'message' => $message
        ], $extra);
    }


    public static function dataValidate($data)
    {
        $flag = true;
        foreach ($data as $key => $value) {
            if ((!isset($value)) || empty(trim($value))) {
                $flag = false;
            }
        }
        return $flag ? true : false;
    }

    public static function lessThanStr($str, $int, $message)
    {
        if (mb_strlen($str) < $int) {
            echo json_encode(self::resultMessage(0, 422, $message));
            return false;
        } else {
            return true;
        }
    }

    public static function moreThanStr($str, $int, $message)
    {
        if ($int < mb_strlen($str)) {
            echo json_encode(self::resultMessage(0, 422, $message));
            return false;
        } else {
            return true;
        }
    }

    public static function mailFormat($email, $message)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(self::resultMessage(0, 422, $message));
            return false;
        } else {
            return true;
        }
    }

    public static function requestType($requestMethod)
    {
        if ($_SERVER["REQUEST_METHOD"] !== $requestMethod) {
            echo json_encode(self::resultMessage(0, 405, 'Method Not Allowed'));
            return false;
        } else {
            return true;
        }
    }
};
