<?php
namespace library\api;


class Upload {

    public function image() {
        $file = request()->file('image');
        !$file && $this->error('没有上传任何文件');
        try {
            $rule = function (\Think\File $file) {
                return date('Y-m-d') . DS . $file->hash('md5');
            };
            $upload = $file->rule($rule)->move(ROOT_PATH . 'public'.DS.'uploads');
            if (!$upload) throw new \Exception($file->getError());
            return $upload->getSaveName();
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

    }
}
?>
