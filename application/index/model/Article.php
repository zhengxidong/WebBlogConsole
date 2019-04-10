<?php
namespace app\index\model;
use think\Model;

/**
 *
 */
class Article extends Model
{
    public function cate()
    {
        return $this->hasMany('cate');
    }
}

