<?php
namespace app\manage\controller;
use think\Controller;
use think\Session;

class Base extends Controller
{
  protected function _initialize()
  {
    parent::_initialize();//继承父类中的初始化操作

    $expire = 3 * 60 * 60;   //session过期时间
    //$expire = 60;   //session过期时间
    if(time() - Session::get('session_start_time') > $expire)
    {
      //销毁所有信息
      Session::clear();
      $this->redirect('admin/index');
      //exit;
    }
    //var_dump(Session::get('admin_id'));
    //var_dump(Session::get('session_start_time'));
    define('ADMIN_ID',Session::get('admin_id'));
  }
  //判断用户是否登录,放在后台的入口：index/index
  protected function isLogin()
  {
    if(empty(ADMIN_ID))
    {
      $this->error('未登录，无权访问！','admin/index');
    }
  }
  //防止用户重复登录 user/login
  protected function alreadyLogin()
  {


    if(!empty(ADMIN_ID))
    {
      $this->error('用户已经登录,请勿重复登录','manage/index');
    }
  }
}
 ?>
