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

$server->on('request', function ($request, $response) {
    $response->end('<h1>HelloWorld!!!</h1>');
});

$server->start();