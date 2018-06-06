<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/6/4
 * Time: 8:06
 */

namespace app\admin\controller;


use app\common\lib\redis\Predis;
use app\common\lib\Util;

class Live
{
    public function push()
    {
        if (empty($_GET)) {
            return Util::show(config('code.error'), 'error');
        }
        $data = [
            'title' => '中国队',
            'logo' => '',
            'content' => !empty($_GET['content']) ? $_GET['content'] : '',
            'image' => !empty($_GET['image']) ? $_GET['image'] : ''
        ];
//        print_r($_GET);
        $client = Predis::getInstance()->sMember(config('redis.live_game_key'));
        foreach ($client as $fd) {
            $_POST['http_server']->push($fd, json_encode($data));
        }
    }
}