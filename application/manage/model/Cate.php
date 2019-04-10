<?php
namespace app\manage\model;
use think\Model;
/**
 *
 */
class Cate extends Model
{
  public function article()
  {
    return $this->belognsTo('article');
  }
}
