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


    public function detail(){
        !IS_GET && $this->redirect("/fg/index/index");
        $id = input('get.id', 0);
        $data = \think\Db::Table('blog')->find($id);
        $user = \think\Db::Table('admin')->find();
        $data['times']+=1;
        try {
            \think\Db::table('blog')->update($data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        $this->assign('user', $user);
        $this->assign('info', $data);
        return $this->fetch();
    }
}
?>
