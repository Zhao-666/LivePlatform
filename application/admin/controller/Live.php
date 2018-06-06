<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/6/4
 * Time: 8:06
 */

namespace app\admin\controller;


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
        $taskData = [
            'method' => 'pushLive',
            'data' => $data
        ];
        $_POST['http_server']->task($taskData);
        return Util::show(config('code.success'), 'success');


    }
}