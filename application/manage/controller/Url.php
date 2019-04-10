<?php
namespace app\manage\controller;
use think\Request;
use think\Db;
use app\manage\model\Url as UrlModel;
use app\manage\model\Cate as CateModel;
/**
 *
 */
class Url extends Base
{
  public function index()
  {
    // $url = new UrlModel();
    // $urlList = $url->order('id','desc')->select();

    $urlList = Db::table('bg_url')
    ->alias('u')
    ->join('bg_cate c','c.cate_id = u.cate_id')
    ->order('id','desc')
    ->select();

    $this->assign('urlList',$urlList);

    return $this->view->fetch();

  }
  //新增数据
  public function add()
  {
      $request = Request::instance();
      $data = $request->post();
      if(empty($data))
      {
        $cate = new CateModel;
        $cateList = $cate->where("cate_name != '首页'")->select();
        $this->assign('cateList',$cateList);
        return $this->view->fetch();
      }
      else
      {
        //模型操作
        $url = new UrlModel;

        $urlAdress        = $data['url'];
        $urlTitle   = $data['url_title'];
        $urlContent = $data['url_content'];
        $cateId     = $data['cate_id'];

        $urlData = [

          'url'         => $urlAdress,
          'url_title'   => $urlTitle,
          'url_content' => $urlContent,
          'cate_id'     => $cateId,
          'created_by'  => 'system',
          'created_on'  => date('Y-m-d H:i:s'),
        ];
        var_dump($urlData);
        $url->data($urlData);

        $url->save();
        $this->redirect('url/index');
        //$this->success('添加成功','links/index');
      }

  }

  public function edit()
  {

    $request = Request::instance();
    if(Request::instance()->isGet())
    {
       $urlId = $request->param('id');

       $urlInfo = UrlModel::get($urlId);

      if(empty($urlInfo))
      {

        $this->redirect('url/index');
        //$this->success('没有此友链信息！','url/index');
      }
        $cate = new CateModel;
        $cateList = $cate->where("cate_name != '首页'")->select();
        $this->assign('cateList',$cateList);
       $this->assign('urlInfo',$urlInfo);

      return $this->view->fetch();
    }
    else if(Request::instance()->isPost())
    {

      $data = $request->post();

      $url = UrlModel::get($data['id']);
      $url->url         = $data['url'];
      $url->url_title   = $data['url_title'];
      $url->url_content = $data['url_content'];
      $url->cate_id     = $data['cate_id'];
      $url->modify_by   = 'system';
      $url->modify_on   = date('Y-m-d H:i:s');
      $url->save();
      $this->redirect('url/index');
      //$this->success('更新成功！','links/index');
    }
    else
    {
      return $this->view->fetch();
    }

  }
}
