<?php
namespace app\bg\Controller;
use think\Controller;
use think\Db;

class Index extends Controller{

    public function index(){
        return $this->fetch();
    }


    public function login(){

        if (!IS_POST) {
            if (session('user.id') > 0) {
                $this->redirect('bg/index/index',302);
            }
            $this->assign('info', '');
            return $this->fetch();
        }

        $username = input('post.username', '');
        $password = input('post.password', '');
        $captcha = input('post.captcha', '');

        if (!captcha_check($captcha)) {
            $this->assign('info', '验证码错误');
            return $this->fetch();
        }

        $info = array();
        if (is_email($username)) {
            $info = Db::table('admin')->where('email', $username)->find();
        } elseif (is_mobile($username)) {
            $info = Db::table('admin')->where('telephone', $username)->find();
        } else {
            $info = Db::table('admin')->where('username', $username)->find();
        }

        if (!$info) {
            $this->assign('info', '用户名或者密码错误');
            return $this->fetch();
        }

        if (md5($password) != $info['password']) {
            $this->assign('info', md5($password));
            return $this->fetch();
        }
        session('user', $info);
        $this->redirect('bg/index/index', 302);
    }


    public function logout(){
        session('user', null);
        header('Location:/login');
    }
}
?>
