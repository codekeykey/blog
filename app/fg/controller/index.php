<?php
namespace app\fg\controller;
use think\controller;

class Index extends controller {

    public function index() {
        $data = \think\Db::Table('blog')->select();
        $user = \think\Db::Table('admin')->find();
        $this->assign('user', $user);
        $this->assign('info', $data);
        return $this->fetch();
    }
}
?>
