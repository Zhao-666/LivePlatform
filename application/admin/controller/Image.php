<?php
/**
 * Created by PhpStorm.
 * User: Next
 * Date: 2018/6/4
 * Time: 7:37
 */

namespace app\admin\controller;


use app\common\lib\Util;

class Image
{
    public function index()
    {
        $file = request()->file('file');
        $info = $file->move('../public/upload');

        if ($info) {
            $data = [
                'image' => config('live.host') . '/upload/' . $info->getSaveName()
            ];
            return Util::show(config('code.success'), 'ok', $data);
        } else {
            return Util::show(config('code.error'), 'error');
        }
    }
}