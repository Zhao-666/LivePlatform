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
//        define('APP_PATH', __DIR__ . '/../application/');
        // 加载基础文件
//        require __DIR__ . '/../thinkphp/base.php';
        //如果Task要使用TP的助手函数就必须在workerStart引入index.php文件，
        //因为助手函数是在App.php类中被初始化的，
        //Task机制是worker异步调用函数，必须在worker启动的时候调用App->init()引入helper.php文件
        require __DIR__ . '/../public/index.php';

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
        $obj = new \app\common\lib\task\Task();
        $method = $data['method'];
        $flag = $obj->$method($data['data']);
//        try {
//            $ret = \app\common\lib\ali\Sms::sendSms($data['phone'], $data['code']);
//        } catch (Exception $e) {
//            echo $e->getMessage();
//        }
//        print_r($ret);
        return $flag;
    }

    public function onFinish($serv, $taskId, $data)
    {
        echo "taskId:$taskId";
        echo "finish-data-success " . $data;
    }

}

new Http();