<?php
/**
 * 监控服务 8811
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/6/9
 * Time: 13:54
 */

class Server
{
    const PORT = 8811;

    public function port()
    {
        $shell = "netstat -anp | grep " . self::PORT
            . " | grep LISTEN | wc -l";
        $result = shell_exec($shell);
        if ($result != 1) {
            echo date('Y-m-d H:i:s') . 'error' . PHP_EOL;
        } else {
            echo date('Y-m-d H:i:s') . 'success' . PHP_EOL;
        }
    }
}

swoole_timer_tick(2000, function ($timer_id) {
    (new Server())->port();
    echo "time-start" . PHP_EOL;
});
