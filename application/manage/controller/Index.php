<?php
namespace app\manage\controller;
use app\manage\controller\Base;
use think\Controller;
use think\Session;
use app\manage\model\AccessRecords as AccessRecordsModel;
class Index extends Base
{
    public function index()
    {

        if(empty(Session::get('admin_id')))
        {
          return $this->view->fetch('admin/index');
        }
        else {
          //获取访问量
          $yesTerDay = date("Y-m-d",strtotime("-1 day"));
          $nowDate = date("Y-m-d");
          $accessRecords = new AccessRecordsModel;
          $yesTerDayAccessList = $accessRecords->where("access_date = '{$yesTerDay}'")->count();

          $nowDateAccessList = $accessRecords->where("access_date = '{$nowDate}'")->count();

          $allAccessList = $accessRecords->count();
          //计算增长率(天)
          if(!empty($yesTerDayAccessList) && !empty($nowDateAccessList) && !empty($allAccessList))
          {
            $accessRecordsCount = ceil((($nowDateAccessList - $yesTerDayAccessList) / $yesTerDayAccessList) / 100);

            $accessRecordsCount = $accessRecordsCount;
            $allAccessList = $allAccessList;
          }
          else {
            $accessRecordsCount = 0;
          }
          $this->assign('accessRecordsCount',$accessRecordsCount);
          $this->assign('allAccessRecords',$allAccessList);
          return $this->view->fetch();
        }

    }

}
