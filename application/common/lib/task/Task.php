<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/6/1
 * Time: 7:38
 */

namespace app\common\lib\task;


use app\common\lib\ali\Sms;
use app\common\lib\Redis;
use app\common\lib\redis\Predis;
use think\Exception;

class Task
{

    /**
     * 发送赛况数据
     * @param $data
     */
    public function pushLive($data, $serv)
    {
        $client = Predis::getInstance()->sMember(config('redis.live_game_key'));
        foreach ($client as $fd) {
            $serv->push($fd, json_encode($data));
        }
    }

    /**
     * 异步发送验证码
     * @param $data
     * @return bool|string
     */
    public function sendSms($data, $serv)
    {
        try {
            $ret = Sms::sendSms($data['phone'], $data['code']);
        } catch (Exception $e) {
            return false;
        }
        if ($ret->Code == 'OK') {
            Predis::getInstance()->set(Redis::smsKey($data['phone']), $data['code'], config('redis.out_time'));
        } else {
            return false;
        }
        return true;
    }
}