<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/5/30
 * Time: 7:01
 */

namespace app\index\controller;


use app\common\lib\ali\Sms;
use app\common\lib\Util;
use Swoole\Coroutine\Redis;
use think\Controller;
use think\Exception;

class Send extends Controller
{
    /**
     * 发送验证码
     */
    public function index()
    {
        $phone = request()->get('phone_num', 0, 'intval');
        if (empty($phone)) {
            return Util::show(config('code.error'), 'empty phone');
        }
        $code = rand(1000, 9999);
        $data = [
            'method' => 'sendSms',
            'data' => [
                'phone' => $phone,
                'code' => $code
            ]
        ];
        $_POST['http_server']->task($data);
        return Util::show(config('code.success'), 'success');
//        try {
//            $ret = Sms::sendSms($phone, $code);
//        } catch (Exception $e) {
//            return Util::show(config('code.error'), $e->getMessage());
//        }
//        if ($ret->Code == 'OK') {
//            $redis = new Redis();
//            $redis->connect(config('redis.host'), config('redis.port'));
//            $redis->set(\app\common\lib\Redis::smsKey($phone), $code, config('redis.out_time'));
//            return Util::show(config('code.success'), 'success');
//        } else {
//            return Util::show(config('code.error'), $ret->Message);
//        }
    }
}