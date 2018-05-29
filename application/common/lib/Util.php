<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/5/30
 * Time: 7:09
 */

namespace app\common\lib;

class Util
{
    public static function show($status, $message = '', $data = [])
    {
        $result = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        return json_encode($result);
    }
}