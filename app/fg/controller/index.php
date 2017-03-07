<?php
namespace app\fg\controller;
use think\controller;

class Index extends controller {

    public function index() {
        $user = \think\Db::Table('admin')->find();
        $advice_data = \think\Db::Table('blog')->order("times desc")->select();
        $new_data = \think\Db::Table('blog')->order("create_time desc")->select();
        $tag = \think\Db::Table('blog')->field('tag,count(tag)')->group('tag')->order('count(tag) desc')->select();
        $this->assign('user', $user);
        $this->assign('advice_data', $advice_data);
        $this->assign('new_data', $new_data);
        $this->assign('tag', $tag);
        $this->assign('data', $advice_data);
        return $this->fetch();
    }


    public function detail(){
        !IS_GET && $this->redirect("/fg/index/index");
        $id = input('get.id', 0);
        $data_id = \think\Db::Table('blog')->find($id);
        $user = \think\Db::Table('admin')->find();
        $advice_data = \think\Db::Table('blog')->order("times desc")->select();
        $new_data = \think\Db::Table('blog')->order("create_time desc")->select();
        $tag = \think\Db::Table('blog')->field('tag,count(tag)')->group('tag')->order('count(tag) desc')->select();
        $data_id['times']+=1;
        try {
            \think\Db::table('blog')->update($data_id);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        $this->assign('user', $user);
        $this->assign('info_id', $data_id);
        $this->assign('advice_data', $advice_data);
        $this->assign('new_data', $new_data);
        $this->assign('tag', $tag);
        return $this->fetch();
    }


    public function wordlist(){
        $count = \think\Db::Table('blog')->count();
        $page = new \library\api\Page($count,7);
        $data = \think\Db::Table('blog')->limit($page->limit)->select();
        $fpage =  $page->fpage();
        $user = \think\Db::Table('admin')->find();
        $advice_data = \think\Db::Table('blog')->order("times desc")->select();
        $new_data = \think\Db::Table('blog')->order("create_time desc")->select();
        $tag = \think\Db::Table('blog')->field('tag,count(tag)')->group('tag')->order('count(tag) desc')->select();
        $this->assign('user', $user);
        $this->assign('advice_data', $advice_data);
        $this->assign('new_data', $new_data);
        $this->assign('data', $data);
        $this->assign('fpage', $fpage);
        $this->assign('tag', $tag);
        return $this->fetch();
    }


    public function taglist(){
        $tagname = input('get.tag', '');
        !$tagname && $this->redirect('index');
        $count = \think\Db::Table('blog')->where('tag', $tagname)->count();
        $page = new \library\api\Page($count,7);
        $data = \think\Db::Table('blog')->where('tag', $tagname)->limit($page->limit)->select();
        $fpage =  $page->fpage();
        $user = \think\Db::Table('admin')->find();
        $advice_data = \think\Db::Table('blog')->order("times desc")->select();
        $new_data = \think\Db::Table('blog')->order("create_time desc")->select();
        $tag = \think\Db::Table('blog')->field('tag,count(tag)')->group('tag')->order('count(tag) desc')->select();
        $this->assign('user', $user);
        $this->assign('advice_data', $advice_data);
        $this->assign('new_data', $new_data);
        $this->assign('tag', $tag);
        $this->assign('data', $data);
        $this->assign('fpage', $fpage);
        return $this->fetch();
    }
}
?>
