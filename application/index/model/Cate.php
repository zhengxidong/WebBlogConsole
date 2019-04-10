<?php
namespace app\index\model;
use think\Model;
class Cate extends Model{
    public function article()
    {
        return $this->belognsTo('article');
    }
}