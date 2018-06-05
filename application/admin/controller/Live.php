<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/6/4
 * Time: 8:06
 */

namespace app\admin\controller;


use app\common\lib\redis\Predis;

class Live
{
    public function push()
    {
//        print_r($_GET);
        $client = Predis::getInstance()->sMember(config('redis.live_game_key'));
        foreach ($client as $fd) {
            $_POST['http_server']->push($fd,'hello');
        }
    }
}