<?php
namespace app\manage\controller;
use app\manage\controller;
use think\Controller;
use think\Request;
use app\manage\model\Term as TermModel;
/**
 *
 */
class Terms extends Base
{
  public function index()
  {
    $term = new TermModel();
    $termList = $term->order('term_id','desc')->select();
    $this->assgin('termList',$termList);
    return $this->view->fetch();
  }
}
