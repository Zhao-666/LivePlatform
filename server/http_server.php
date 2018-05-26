<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/5/10
 * Time: 22:55
 */

$server = new swoole_http_server('0.0.0.0', 8811);

$server->set(
    [
        'enable_static_handler' => true,
        'document_root' =>
            '/home/work/htdocs/LivePlatform/public/static',
    ]
);

$server->on('WorkerStart', function (swoole_server $server, $worker_id) {
    // 加载基础文件
    require __DIR__ . '/../thinkphp/base.php';
});

$server->on('request', function ($request, $response) {
    if (isset($request->server)) {
        foreach ($request->server as $key => $value) {
            $_SERVER[strtoupper($key)] = $value;
        }
    }
    if (isset($request->header)) {
        foreach ($request->header as $key => $value) {
            $_SERVER[strtoupper($key)] = $value;
        }
    }
    if (!empty($_GET)) {
        unset($_GET);
    }
    if (isset($request->get)) {
        foreach ($request->get as $key => $value) {
            $_GET[$key] = $value;
        }
    }
    if (!empty($_GET)) {
        unset($_POST);
    }
    if (isset($request->post)) {
        foreach ($request->post as $key => $value) {
            $_POST[$key] = $value;
        }
    }
    ob_start();
    try {
        \think\Container::get('app')->run()->send();
        $ret = ob_get_contents();
        ob_end_clean();
    } catch (\Exception $e) {
        $ret = $e->getMessage();
    }
    $response->end($ret);
});

$server->start();