<?php
namespace library\api;


class Upload {

    public function image() {
        $file = request()->file('image');
        if(!$file){
            return -1;
        }
        try {
            $rule = function (\Think\File $file) {
                return date('Y-m-d') . DS . $file->hash('md5');
            };
            $upload = $file->rule($rule)->move(ROOT_PATH . 'public'.DS.'uploads');
            if (!$upload) throw new \Exception($file->getError());
            return $upload->getSaveName();
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }

    }
}
?>
