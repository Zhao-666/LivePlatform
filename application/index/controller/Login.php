<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/5/30
 * Time: 7:53
 */

namespace app\index\controller;


use app\common\lib\Redis;
use app\common\lib\redis\Predis;
use app\common\lib\Util;

class Login
{
    public function index()
    {
        $phone = request()->get('phone_num');
        $code = request()->get('code');
        if (empty($phone) || empty($code)) {
            return Util::show(config('code.error'), 'phone or code is empty');
        }
        $redisCode = Predis::getInstance()->get(Redis::smsKey($phone));
        if ($redisCode == $code) {
            $data = [
                'user' => $phone,
                'srcKey' => md5(Redis::userKey($phone)),
                'time' => time(),
                'isLogin' => true
            ];
            Predis::getInstance()->set(Redis::userKey($phone), $data);
            return Util::show(config('code.success'), 'ok', $data);
        } else {
            return Util::show(config('code.error'));
        }
    }
}