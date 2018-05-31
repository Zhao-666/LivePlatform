<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/6/1
 * Time: 7:05
 */

class Http
{
    const HOST = '0.0.0.0';
    const PORT = 8811;

    public $http = null;

    public function __construct()
    {
        $this->http = new swoole_http_server(self::HOST, self::PORT);
        $this->http->on('workerStart', [$this, 'onWorkerStart']);
        $this->http->on('request', [$this, 'onRequest']);
        $this->http->on('close', [$this, 'onClose']);
        $this->http->on('task', [$this, 'onTask']);
        $this->http->on('finish', [$this, 'onFinish']);
        $this->http->set([
            'worker_num' => 4,
            'task_worker_num' => 4,
            'enable_static_handler' => true,
            'document_root' =>
                '/home/work/htdocs/LivePlatform/public/static',
        ]);

        $this->http->start();
    }

    public function onWorkerStart($http, $worker_id)
    {
        define('APP_PATH', __DIR__ . '/../application/');
        // 加载基础文件
        require __DIR__ . '/../thinkphp/base.php';
    }

    public function onRequest($request, $response)
    {
        echo 'success';
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
        $_GET = [];
        if (isset($request->get)) {
            foreach ($request->get as $key => $value) {
                $_GET[$key] = $value;
            }
        }
        $_POST = [];
        if (isset($request->post)) {
            foreach ($request->post as $key => $value) {
                $_POST[$key] = $value;
            }
        }
        $_POST['http_server'] = $this->http;
        ob_start();
        try {
            \think\Container::get('app')->run()->send();
            $ret = ob_get_contents();
            ob_end_clean();
        } catch (\Exception $e) {
            $ret = $e->getMessage();
        }
        $response->end($ret);
    }

    public function onClose($http, $fd)
    {
        echo "clientid:$fd\n";
    }

    public function onTask($serv, $taskId, $workerId, $data)
    {
        try {
            $ret = \app\common\lib\ali\Sms::sendSms($data['phone'], $data['code']);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
//        print_r($ret);
        return 'on task finish';
    }

    public function onFinish($serv, $taskId, $data)
    {
        echo "taskId:$taskId";
        echo "finish-data-success " . $data;
    }

}

new Http();