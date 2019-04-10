<?php
namespace app\manage\controller;
use app\manage\controller\Base;
use think\Controller;
use think\Db;
use think\Request;
use app\manage\model\Article as ArticleModel;
use app\manage\model\Cate as CateModel;
use app\manage\model\TermsForArticle as TermsForArticleModel;
use app\manage\model\Tag as TagModel;
use app\manage\model\ArticleForTag as ArticleForTagModel;
class Article extends Base
{
    public function index()
    {
        //获取文章数据
        //$articleData = Db::table('bg_article')->select();
        $article = new ArticleModel();

        $articleData = $article->order('article_modified_on','desc')->select();
        $this->assign('articleData',$articleData);

        return $this->view->fetch();
    }

    //新增数据
    public function add()
    {
        $request = Request::instance();
        $data = $request->post();

        if(empty($data))
        {
          //获取所有栏目
          $cate = new CateModel();
          $cateList = $cate->where('cate_id != 1')->select();
          $this->assign('cateList',$cateList);

          //获取所有标签
          $tag = new TagModel;
          $tagList = $tag->select();
          $this->assign('tagList',$tagList);

          return $this->view->fetch();
        }
        else
        {

          //DB操作
          // $data['article_author'] = 111;
          // $data['article_date'] = date('Y-m-d H:i:s');
          // $data['article_content'] = $data['test-editormd-markdown-doc'];
          // $data['article_title'] = 'test';
          // $data['article_excerpt'] = '';
          // $data['article_status'] = 'open';
          // $data['article_password'] = '88888';
          // $data['article_name'] = '';
          // unset($data['test-editormd-markdown-doc']);
          // unset($data['title']);
          // unset($data['test-editormd-html-code']);
          // Db::table('bg_article')->insert($data);

          if(!isset($data['cate_id']) && !empty($data['cate_id'])
          && !isset($data['title']) && !empty($data['title']))
          {
            $this->error('请填写完整','article/index');
          }
          //模型操作
          $article = new ArticleModel;
          $cateId = $data['cate_id'];

          $articleTitle   = $data['title'];
          $articleExcerpt = $data['excerpt'];
          $articleContent = $data['test-editormd-markdown-doc'];
          $articleStatus  = empty($data['status']) ? '' : $data['status'];
          $articlePassword = $data['password'];
          $articleName     = $data['name'];

          try{

          
            $articleData = [

              'article_title'   => $articleTitle,                       //文章标题
              'article_author'  => '1',                                 //文章作者ID
              'article_date'    => date('Y-m-d H:i:s'),                   //文章发布时间
              'article_excerpt' => $articleExcerpt,                   //文章摘录
              'article_content' => $articleContent,//文章内容
              'article_status'  => (empty($articleStatus)) ? 'close':'open',                              //文章状态，是否公开
              'article_password'=> $articlePassword,                        //文章密码
              'article_name'    => $articleName,                         //文章缩略名
              'cate_id'         => $cateId
            ];

            //unset($data['test-editormd-markdown-doc']);
            //unset($data['title']);
            //unset($data['test-editormd-html-code']);

            $article->data($articleData);
            $article->save();

            if(isset($data['tagId']) && !empty($data['tagId']))
            {
              foreach ($data['tagId'] as $key => $id) {
                //保存标签
                $articleForTag = new ArticleForTagModel;
                $articleForTag->article_id = $article->id;
                $articleForTag->tag_id = $id;
                $articleForTag->created_by = 'system';
                $articleForTag->created_on = date("Y-m-d H:i:s");
                if(!$articleForTag->save())
                {
                  $this->error('网络错误,标签添加失败！','article/index');
                }
              }

            }

        }
        catch(\Exception $e){
          $this->error('发生错误:'.$e->getMessage(),'article/index');
        }

          $this->redirect('article/index');
          //$this->success('发布成功','article/index');
        }

    }


    //渲染编辑界面
    //public function
    //编辑文章
    public function edit()
    {
      //$request = Request::instance();
      //$data = $request->post();
      //var_dump($data);
      //exit;
      //$request->param('id');
      $request = Request::instance();
      if(Request::instance()->isGet())
      {
         $articleId = $request->param('id');
         $articleInfo = ArticleModel::get($articleId);

         $tag = new TagModel;
         $tagList = $tag->select();
        if(empty($articleInfo))
        {
          $this->redirect('article/index');
          //$this->success('没有此文章信息！','article/index');
        }
         $this->assign('articleInfo',$articleInfo);
         //获取所有栏目
         $cate = new CateModel();
         $cateList = $cate->where('cate_id != 1')->select();

         $this->assign('cateList',$cateList);

         //获取所有标签
         $this->assign('tagList',$tagList);

         //获取当前文章所有标签
         $articleForTag = new ArticleForTagModel;
         $articleForTagList = $articleForTag->where("article_id = $articleId")->select();
         $this->assign('articleForTagList',$articleForTagList);

        return $this->view->fetch();
      }
      else if(Request::instance()->isPost())
      {

        $data = $request->post();
        $articleId = $data['id'];
        $article = ArticleModel::get($articleId);
        $article->article_title    = $data['title'];
        $article->article_excerpt  = $data['excerpt'];
        $article->article_content  = $data['test-editormd-markdown-doc'];
        $article->article_status   = (empty($data['status'])) ? 'close':'open';
        $article->article_password = $data['password'];
        $article->article_name     = $data['name'];
        $article->article_modified_on = date('Y-m-d H:i:s');
        $article->cate_id = $data['cate_id'];
        $article->save();

        //更新标签
        //获取当前文章所有标签
          $articleForTag = new ArticleForTagModel;
          $articleForTagList = $articleForTag->where("article_id = $articleId")->select();
          $tagId = [];
          foreach ($articleForTagList as $value) {
            $tagId[$value->id] = $value->tag_id;
          }
          $data['tagId'] = !empty($data['tagId']) ? $data['tagId'] : [];

          $add = array_diff($data['tagId'],$tagId);

          $delete = array_diff($tagId,$data['tagId']);

          if(!empty($add))
          {
            foreach ($add as $id) {
              $articleForTag->article_id = $articleId;
              $articleForTag->tag_id = $id;
              $articleForTag->created_by = 'system';
              $articleForTag->created_on = date("Y-m-d H:i:s");
              $articleForTag->save();
            }
          }
          if(!empty($delete))
          {
            $tagId = array_keys($delete);
              foreach ($tagId as $id) {
                $articleForTag = ArticleForTagModel::get($id);
                $articleForTag->delete();
              }
          }
          
        $this->redirect('article/index');

      }
      else
      {
        return $this->view->fetch();
      }

    }
    //点击改变文章状态
    public function changeState()
    {
      $request = Request::instance()->post();
      if(!empty($request))
      {
        if($request['status'] == '是')
        {
          $status = '否';
        }
        else {
          $status = '是';
        }
        $article = ArticleModel::get($request['articleId']);
        if(!empty($article))
        {
          $article->article_status = $status;
          $article->save();
        }

        return json($status);
      }
      return json(['error'=>0]);
    }
}
