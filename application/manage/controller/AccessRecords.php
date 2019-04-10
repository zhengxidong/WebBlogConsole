<?php
namespace app\manage\controller;
use app\manage\controller\Base;
use think\Controller;
use think\Session;
use think\Db;
use app\manage\model\AccessRecords as AccessRecordsModel;
class AccessRecords extends Base
{
    public function index()
    {
      $accessRecords = new AccessRecordsModel();
      //$accessRecordsList = $accessRecords->order('access_time','desc')->select();
      $accessRecordsList = Db::table('bg_access_records')
      ->order('access_time','asc')
      ->select();
      //var_dump($accessRecordsList);
      $this->assign('accessRecordsList',$accessRecordsList);
      return $this->view->fetch();
    }

}
