<?php
namespace app\bg\Controller;
use think\Controller;
use think\Db;

class User extends Controller{


    public function index(){
        $user_id = session('user.id', '');
        $info = Db::Table('admin')->where('id', $user_id)->find();
        $this->assign('info', $info);
        return $this->fetch();
    }
}
?>
