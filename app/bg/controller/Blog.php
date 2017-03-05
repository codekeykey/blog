<?php
namespace app\bg\Controller;
use think\Controller;

class Blog extends controller{

    public function index() {
        $count = \think\Db::Table('blog')->count();
        $page = new \library\api\Page($count);
        $info = \think\Db::Table('blog')->limit($page->limit)->select();
        $feyepage = $page->fpage();
        $this->assign('info', $info);
        $this->assign('page', $feyepage);
        return $this->fetch();
    }


    public function add() {
        $rule = [
            ['title', 'require', '标题必须有'],
            ['content', 'require', '内容必须有'],
            ['tag', 'require', '标签必须有']
        ];
        if (IS_POST) {
            $data = input('post.', '');
            $validate = new \think\Validate($rule);
            $validate->batch(true);
            $upload = new \library\api\Upload();
            $image = $upload->image();
            $data['image'] = $image;
            if (!$validate->check($data)) {
                $this->assign('error', $validate->getError());
            } else{
                $data['create_time'] = time();
                $data['update_time'] = $data['create_time'];
                $data['author'] = session('user.id');
                if (\think\Db::Table('blog')->insert($data)) {
                    $this->assign('info', '添加成功');
                } else {
                    $this->assign('info', '添加失败');
                }
            }
        }
        return $this->fetch();
    }


    public function delete(){
        $blog_id = input('param.id', '');
        !$blog_id && $this->redirect('index');

        if (\think\Db::Table('blog')->delete($blog_id)) {
            $this->success("删除".$blog_id."成功", '/bg/blog/index');
        } else {
            $this->error("删除".$blog_id."失败", '/bg/blog/index');
        }
    }


    public function edit(){
        $blog_id = input('param.id', 0);
        !$blog_id && $this->redirect('index');

        if (!IS_POST) {
            $data = \think\Db::Table('blog')->find($blog_id);
        } else {
            $data = input('post.', '');
            $data['update_time'] = time();
            $upload = new \library\api\Upload();
            $image = $upload->image();
            $image == -1 && $this->assign("info", '修改失败');
            $data['image'] = $image;
            if (\think\Db::Table('blog')->update($data)) {
                $this->success("修改".$data['id']."成功", '/bg/blog/index');
            } else {
                $this->assign("info", '修改失败');
            }
        }
        $this->assign('data', $data);
        return $this->fetch();
    }
}


 ?>
