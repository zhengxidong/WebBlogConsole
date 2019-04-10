<?php
namespace app\manage\controller;
use app\manage\controller\Base;
use think\Controller;
use think\Request;
use think\Session;
use app\manage\model\Admin as AdminModel;
/**
 *
 */
//class Admin extends Base
class Admin extends Controller
{
  public function index()
  {
    //$this->alreadyLogin();
    return $this->view->fetch();
    // $userInfo = Request::instance();
    // if(!empty($userInfo))
    // {
    //   $this->alreadyLogin();
    //   return $this->view->fetch('index/index');
    // }
    // else
    // {
    //   return $this->view->fetch();
    // }

  }
  public function checkLogin(Request $request)
  {
    $data = $request->param();
    if(!empty($data))
    {

      $adminInfo = AdminModel::get(
        [
          'admin_user'    =>$data['user_name'],
          'admin_password'=>md5($data['user_pawd'])
        ]
      );

      if(empty($adminInfo))
      {
        $this->redirect('admin/index');
        //$this->error('管理员不存在,请重新登录！','admin/index');
      }
      else
      {
        //Session保存管理员信息
        Session::set('admin_id',$adminInfo->id);
        Session::set('admin_info',$adminInfo->getData());
        Session::set('session_start_time',time());   //保存当前时间，以便判断session会话过期
        $this->redirect('index/index');
        //$this->success('登录成功!','index/index');
      }
    }
    else
    {
      $this->redirect('admin/index');
      //$this->error('管理员不存在,请重新登录！','admin/index');
    }
  }
  public function logout()
  {
    //Session::delete('admin_id');
    //Session::delete('admin_info');
    Session::clear();
    $this->redirect('admin/index');
    //$this->success('注销成功!','admin/index');
  }
}
