<?php
/**
 * @desc 在模块初始化时
 * @author 郑孝晶<843368288@843368288.net>
 * @since 2016-12-13
 */
namespace app\bg\common\Behaviors;

class Init {
    //行为执行入口文件
    public function run(&$param){
        $request = \think\Request::instance();
        define('MODULE_NAME', $request->module());
        define('CONTROLLER_NAME', $request->controller());
        define('ACTION_NAME', $request->action());
        define('IS_GET', $request->isGet());
        define('IS_POST', $request->isPost());
        define('IS_AJAX', $request->isAjax());

        if(session('user') == null && ACTION_NAME != 'login') {
            header('Location: /login');
        }
    }

}
?>
