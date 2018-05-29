<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/5/30
 * Time: 7:30
 */

namespace app\common\lib;


class Redis
{
    /**
     * 验证码前缀
     * @var string
     */
    public static $pre = 'sms_';

    public static function smsKey($phone)
    {
        return self::$pre . $phone;
    }
}