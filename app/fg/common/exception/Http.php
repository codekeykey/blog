<?php
namespace app\fg\common\exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ErrorException;

class Http extends Handle {

    public function render(\Exception $e) {
        if (config('app_debug') !== true) {
            switch ($e) {
                case $e instanceof HttpException :
                case $e instanceof ErrorException :
                default:
                $request = \think\Request::instance();
                if ($request->isAjax() === true) {
                    \think\Response::create(['code'=>'0', 'msg'=>'error', 'data'=>''],'json', '404')->send();
                } else {
                    $data = (new \think\View())->engine(['view_path' => APP_PATH . 'fg/common/layout/'])->fetch('/404');
                    \think\Response::create($data, '', '404')->send();
                }
            }
        } else {
            return parent::render($e);
        }
    }

}
