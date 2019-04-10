<?php
namespace app\manage\controller;
use think\Request;
use app\manage\model\Links as LinksModel;
/**
 *
 */
class Links extends Base
{
  public function index()
  {
    $links = new LinksModel();
    $linksList = $links->order('link_id','desc')->select();
    $this->assign('linksList',$linksList);

    return $this->view->fetch();

  }
  //新增数据
  public function add()
  {
      $request = Request::instance();
      $data = $request->post();
      if(empty($data))
      {
        return $this->view->fetch();
      }
      else
      {
        //模型操作
        $links = new LinksModel;

        $linkUrl         = $data['link_url'];
        $linkName        = $data['link_name'];
        $linkImage       = $data['link_image'];
        $linkTarget      = $data['link_target'];
        $linkDescription = $data['link_description'];
        $linkVisible = $data['link_visible'];
        $linkRss = $data['link_rss'];

        $linksData = [

          'link_url'        => $linkUrl,
          'link_name'       => $linkName,
          'link_image'      => $linkImage,
          'link_target'     => $linkTarget,
          'link_description'=> $linkDescription,
          'link_visible'    => $linkVisible,
          'link_rss'        => $linkRss,
          'link_create'     => date('Y-m-d H:i:s'),
        ];

        $links->data($linksData);

        $links->save();
        $this->redirect('links/index');
        //$this->success('添加成功','links/index');
      }

  }

  public function edit()
  {

    $request = Request::instance();
    if(Request::instance()->isGet())
    {
       $linksId = $request->param('link_id');

       $linksInfo = LinksModel::get($linksId);

      if(empty($linksInfo))
      {
        $this->redirect('links/index');
        //$this->success('没有此友链信息！','links/index');
      }
       $this->assign('linksInfo',$linksInfo);

      return $this->view->fetch();
    }
    else if(Request::instance()->isPost())
    {

      $data = $request->post();

      $links = linksModel::get($data['link_id']);
      $links->link_url         = $data['link_url'];
      $links->link_name        = $data['link_name'];
      $links->link_image       = $data['link_image'];
      $links->link_target      = $data['link_target'];
      $links->link_description = $data['link_description'];
      $links->link_visible     = $data['link_visible'];
      $links->link_rss         = $data['link_rss'];
      $links->link_updated     = date('Y-m-d H:i:s');
      $links->save();
      $this->redirect('links/index');
      //$this->success('更新成功！','links/index');
    }
    else
    {
      return $this->view->fetch();
    }

  }
}
