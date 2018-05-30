<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/5/30
 * Time: 8:02
 */

namespace app\common\lib\redis;


use think\Exception;

class Predis
{
    public $redis = "";
    private static $_instance = null;

    public static function getInstance()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        $this->redis = new \Redis();
        $result = $this->redis->connect(config('redis.host'),
            config('redis.port'),
            config('redis.timeOut'));
        if ($result === false) {
            throw new Exception('redis connect error');
        }
    }

    public function set($key, $value, $time = 0)
    {
        if (!$key) {
            return '';
        }
        if (is_array($value)) {
            $value = json_encode($value);
        }
        if (!$time) {
            return $this->redis->set($key, $value);
        }
        return $this->redis->setex($key, $time, $value);
    }

    public function get($key)
    {
        if (!$key) {
            return '';
        }
        return $this->redis->get($key);
    }
}