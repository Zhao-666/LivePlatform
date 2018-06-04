<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/6/1
 * Time: 7:05
 */

class Ws
{
    const HOST = '0.0.0.0';
    const PORT = 8811;

    public $ws = null;

    public function __construct()
    {
        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);
        $this->ws->on('workerStart', [$this, 'onWorkerStart']);
        $this->ws->on('request', [$this, 'onRequest']);
        $this->ws->on('open', [$this, 'onOpen']);
        $this->ws->on('message', [$this, 'onMessage']);
        $this->ws->on('close', [$this, 'onClose']);
        $this->ws->on('task', [$this, 'onTask']);
        $this->ws->on('finish', [$this, 'onFinish']);
        $this->ws->set([
            'worker_num' => 4,
            'task_worker_num' => 4,
            'enable_static_handler' => true,
            'document_root' =>
                '/home/work/htdocs/LivePlatform/public',
        ]);

        $this->ws->start();
    }

    /**
     * 监听ws连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws, $request)
    {
        \app\common\lib\redis\Predis::getInstance()
            ->sAdd(config('redis.live_game_key'), $request->fd);
        var_dump($request->fd);
    }

    public function onClose($ws, $fd)
    {
        \app\common\lib\redis\Predis::getInstance()
            ->sRem(config('redis.live_game_key'), $fd);
        echo "clientid:$fd\n";
    }

    /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws, $frame)
    {
        echo "client-push-message: $frame->data \n";
        $this->ws->push($frame->fd, "server-push:" . date('Y-m-d H:i:s'));
    }

    public function onWorkerStart($ws, $worker_id)
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
        $_FILES = [];
        if (isset($request->files)) {
            foreach ($request->files as $key => $value) {
                $_FILES[$key] = $value;
            }
        }
        $_POST['http_server'] = $this->ws;
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

new Ws();