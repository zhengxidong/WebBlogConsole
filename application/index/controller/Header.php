<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\Article as ArticleModel;
use app\index\model\Cate as CateModel;
use app\index\model\Tag as TagModel;
class Header extends controller
{
  public function header()
  {
    //获取栏目
    $cate = new CateModel();
    $cateList = $cate->order('cate_id','asc')->select();
    $this->assign('cateList',$cateList);

    //获取最新添加的前5条文章
    $article = new ArticleModel;
    $articleListMsg = $article->where("article_status = 'open'")->order('id','desc')->limit(5)->select();
    //var_dump($articleListMsg);
    //exit;
    $this->assign('articleListMsg',$articleListMsg);

    return $this->view->fetch();
  }

  public function show($id)
  {
    //$cateInfo = CateModel::get($id);
    $cate = new CateModel();
    $cateList = $cate->order('cate_id','asc')->select();
    $this->assign('cateList',$cateList);

    //获取所有标签
    $tag = new TagModel;
    $tagList = $tag->where('status = 1')->select();
    $this->assign('tagList',$tagList);
    //$articleInfoList = ArticleModel::all(['cate_id'=>$id]);

    //获取最新添加的前5条文章
    $article = new ArticleModel;
    $articleListMsg = $article->where("article_status = 'open'")->order('id','desc')->limit(5)->select();
    $this->assign('articleListMsg',$articleListMsg);

    $cateId = ($id == 1) ? '' : "and a.cate_id={$id}";
    $articleList = Db::table('bg_article')
      ->alias('a')
      ->join('bg_cate c','c.cate_id = a.cate_id')
      ->where("a.article_status ='open' {$cateId}")
      ->order('a.id','desc')
      ->limit(20)
      ->select();
    $this->assign('dataTotal',20);
    $this->assign('articleList',$articleList);
    return $this->view->fetch('index/index');
  }
}
