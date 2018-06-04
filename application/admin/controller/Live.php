<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/6/4
 * Time: 8:06
 */

namespace app\admin\controller;


class Live
{
    public function push()
    {
        print_r($_GET);

        $_POST['http_server']->push(17,'hello_world!!!');
    }
}