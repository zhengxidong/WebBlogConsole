<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Cookie;
use app\index\model\Article as ArticleModel;
use app\index\model\Cate as CateModel;
use app\manage\model\AccessRecords as AccessRecordsModel;
use app\index\model\Tag as TagModel;
use app\index\model\ArticleForTag as ArticleForTagModel;
class Index extends Controller
{
    public function index()
    {

        //测试获取地理位置
        //print_r(getCity("123.125.71.73"));
        //var_dump(getAddress('223.211.94.217'));
        //获取所有文章数据
        //$articleData = Db::table('bg_article')->select();

        //模型操作查询
        //$article = new ArticleModel();
        //$articleList = $article->order('id','desc')->select();
        //$articleList = ArticleModel::all();
        //存储访问记录ip地址
        $request = Request::instance();
        $ip = $request->ip(0,true);
        if($ip)
        {
          $ipInfo = getAddress($ip);

          if($ipInfo)
          {

            if($ipInfo->area)
            {
              $area = $ipInfo->area; //区
            }
            if($ipInfo->county)
            {
                $area = $ipInfo->county;   //县
            }
            $accessRecords = new AccessRecordsModel;
            $accessRecords->ip            = $ip;
            $accessRecords->article_id    = 0;
            $accessRecords->country_name  = (!empty($ipInfo->country)) ? $ipInfo->country : null;
            $accessRecords->province_name = (!empty($ipInfo->region)) ? $ipInfo->region : null;
            $accessRecords->city_name     = (!empty($ipInfo->city)) ? $ipInfo->city : null;
            $accessRecords->area_name     = (!empty($area)) ? $area : null;
            $accessRecords->access_time   = date("Y-m-d H:i:s");
            $accessRecords->access_date   = date("Y-m-d");
            $accessRecords->save();
          }

        }

        //链式操作联合查询
        $articleList = Db::table('bg_article')
          ->alias('a')
          ->join('bg_cate c','c.cate_id = a.cate_id')
          ->where("a.article_status ='open'")
          ->order('a.id','desc')
          ->limit(20)
          ->select();
          //var_dump($articleList);
          //exit;
          //$dataTotal = count($articleList);
          $this->assign('dataTotal',20);
      $this->assign('articleList',$articleList);

      //获取栏目
      $cate = new CateModel();
      $cateList = $cate->order('cate_id','asc')->select();
      $this->assign('cateList',$cateList);

      //获取所有标签
      $tag = new TagModel;
      $tagList = $tag->where('status = 1')->select();
      $this->assign('tagList',$tagList);

      //获取最新添加的前5条文章
      $article = new ArticleModel;
      $articleListMsg = $article->where("article_status = 'open'")->order('id','desc')->limit(5)->select();
      $this->assign('articleListMsg',$articleListMsg);

      return $this->view->fetch();
    }

    //加载更多文章
    public function moreArticleList()
    {

      $data = Request::instance()->post();
      if($data)
      {
        $size = $data['total'];
        $page = $data['paged'];

            //2   =  1 * 2
        $offset = $page * $size;
        //获取更多文章列表
        $articleList = Db::table('bg_article')
          ->alias('a')
          ->join('bg_cate c','c.cate_id = a.cate_id')
          ->where("a.article_status ='open'")
          ->order('a.id','desc')
          ->limit($offset,$size)
          ->select();

        //echo   Db::getLastSql();
        //var_dump($articleList);
        //exit;
        if(!empty($articleList))
        {
          $articleListText = '';
          foreach ($articleList as $key => $value) {


            //内容截取
            $articleContent = mb_substr($value['article_content'],0,175);
            //var_dump($articleContent);
            //exit;
            $action = 'index/article_list';
            $id = 'id';
            //内容过滤
            $articleContent = str_replace(['#','<pre>','<pre'],'',$articleContent);
            $articleId = $value['id'];

            //$article_details_url =  "{:url('{$action}',['{$id}'=>{$articleId}])}";
            $article_details_url =  "index/index/article_details/id$articleId.html";
            //echo $article_details_url;
            //exit;
            $articleListText .= '<div class="ajax-load-con content wow bounceInUp" style="visibility: visible; animation-name: bounceInUp;">
      								<div class="content-box posts-gallery-box">
      									<div class="posts-gallery-content"> '.
      										'<h2><a href='.'"'.$article_details_url.'"'." title='{$value['article_title']}'>{$value['article_title']}</a></h2> ".

      										'<div class="posts-gallery-text">'.$articleContent.'</div>
      										<div class="posts-default-info posts-gallery-info">
      											<ul> '.
      												"<li class='ico-cat'><i class='icon-list-2'></i> <a href=''>{$value['cate_name']}</a></li>
      												<li class='ico-time'><i class='icon-clock-1'></i> {$value['article_date']}</li>
      												<li class='ico-eye'><i class='icon-eye-4'></i>{$value['article_views']}</li>
      												<li class='ico-like'><i class='icon-heart'></i> {$value['article_like']}</li> ".
      											'</ul>
      										</div>
      									</div>
      								</div>
      							</div>';
                    //echo $text;
                    //exit;
          }
        }
        else {
          $articleListText = 0;
        }
      }
      $articleData = [
        'code'=>200,
        'msg' =>'成功',
        'next' => $page + 1,
        'total' => $offset + 2,
        'postlist' => $articleListText

      ];
      return json($articleData);
    }

    public function article_details($id)
    {

      // var_dump(date("h:i:s"));
      // var_dump(strtotime());
      // exit;
      //获取栏目列表
      $cate = new CateModel();
      $cateList = $cate->order('cate_id','asc')->select();
      $this->assign('cateList',$cateList);

      //获取当前文章标签
      $currentArticleTagList = Db::table('bg_article_for_tag')
      ->alias('aft')
      ->join('bg_tag t','aft.tag_id = t.id')
      ->where("aft.article_id = $id")
      ->where('t.status = 1')
      ->select();
      $this->assign('currentArticleTagList',$currentArticleTagList);

      $articleInfo = ArticleModel::get($id);

      $cateInfo = CateModel::get($articleInfo->cate_id);
      $this->assign('cateInfo',$cateInfo);

      //获取所有标签
      $tag = new TagModel;
      $tagList = $tag->where('status = 1')->select();
      $this->assign('tagList',$tagList);

      $this->assign('articleInfo',$articleInfo);

      //获取最新添加的前5条文章
      $article = new ArticleModel;
      $articleListMsg = $article->where("article_status = 'open'")->order('id','desc')->limit(5)->select();
      $this->assign('articleListMsg',$articleListMsg);

      //文章访问量
      $request = Request::instance();
      //$expire = 24 * 60 * 60;

      //计算过期时间,距离第二天剩余时间作为过期时间
      $expire = strtotime(date("Y-m-d 12:00:00")) - time();
      //$expire = floor($expire/3600);
      //var_dump(floor($expire/60/60));
      //exit;
      //$expire = date("Y-m-d");
      $ip = $request->ip(0,true);
      $newIp = str_replace('.','_',$ip);

      //ip地址+文章ID
      $name = $newIp.'_'.$id;


      if(!Cookie::has($name,'views_'))
      {
        //没有访问量过，则数据库文章访问量加1
        $articleViews = $articleInfo->article_views + 1;
        //echo "浏览数".$articleViews."<br/>";
        //$value = $articleViews;
        $cookieValue = $articleViews.'_'.$expire;//浏览量+过期时间戳

        //echo $cookieValue;
        //exit;
        Cookie::set($name,$cookieValue,['prefix'=>'views_','expire'=>$expire]);

        $articleInfo->article_views = $articleViews;
        $articleInfo->save();
        //浏览量
        $this->assign('articleViews',$articleViews);
      }
      else
      {

        $value = Cookie::get($name,'views_');
        $val = explode('_',$value);

        $this->assign('articleViews',$val[0]);
      }

      //访问记录
      if($ip)
      {
        //查询当天访问记录是否已经存在该ip记录
        $value = Cookie::get($name,'views_');
        $val = explode('_',$value);
        $today = date("Y-m-d");

        $map['ip'] =  $ip;
        $map['article_id'] = $id;
        $map['access_date'] = $today;
        $access = new AccessRecordsModel;
        $accessInfo = $access->where($map)->select();
        //$accessInfo = AccessRecordsModel::get(['ip'=>$ip,'article_id'=>$id,'access_date'=>'{$today}']);

        //如果数据库不存在当前ip地址及文章id和当前日期
        if(empty($accessInfo))
        {
          $ipInfo = getAddress($ip);

          if($ipInfo)
          {
            if($ipInfo->area)
            {
              $area = $ipInfo->area; //区
            }
            if($ipInfo->county)
            {
                $area = $ipInfo->county;   //县
            }
            $accessRecords = new AccessRecordsModel;
            $accessRecords->ip            = $ip;
            $accessRecords->article_id    = $articleInfo->id;
            $accessRecords->article_name  = $articleInfo->article_title;
            $accessRecords->country_name  = (!empty($ipInfo->country)) ? $ipInfo->country : null;
            $accessRecords->province_name = (!empty($ipInfo->region)) ? $ipInfo->region : null;
            $accessRecords->city_name     = (!empty($ipInfo->city)) ? $ipInfo->city : null;
            $accessRecords->area_name     = (!empty($area)) ? $area : null;
            $accessRecords->access_time   = date("Y-m-d H:i:s");
            $accessRecords->access_date   = date("Y-m-d");
            $accessRecords->save();
          }
        }
        //AccessRecordsModel::whereTime('access_time','>',["$analyze_firstday 00:00:00"])->select();
      }

      $this->assign('articleId',$id);
      //点赞
      $this->assign('articleLike',$articleInfo->article_like);
      //评论
      $this->assign('commentCount',$articleInfo->comment_count);
      //关闭评论
      $this->assign('isOpen',1);
      return $this->view->fetch();
    }
    //文章点赞
    public function like()
    {
      $request = Request::instance();

      if($request->post()['action'] == 'suxing_like')
      {
        $articleId = $request->post()['um_id'];
        //如果cookie中找到，则返回提示
        if($request->ip())
        {
          $expire = 24 * 60 * 60;
          $ip = str_replace('.','_',$request->ip());
          $name = $ip.'_'.$articleId;
          if(!Cookie::has($name,'like_'))
          {
            Cookie::set($name,$name,['prefix'=>'like_','expire'=>$expire]);
            $articleInfo = ArticleModel::get($articleId);
            if(!empty($articleInfo))
            {
              $likeCount = $articleInfo->article_like + 1;
              $articleInfo->article_like = $likeCount;
              $articleInfo->save();
            }
            return json($likeCount);
          }
          else
          {

            return json(['error'=>'1']);
          }
        }

      }

    }

    //搜索
    public function search_list()
    {
      $request = Request::instance();

      $searchName = $request->get('search_name');

      // $article = new ArticleModel;
      // $articleList = $article->where('article_title&article_content','like',"%$searchName%")
      // ->order('id','desc')
      // ->select();
      // var_dump($articleList);
      // $this->assign('articleList',$articleList);

      //链式操作联合查询
      $articleList = Db::table('bg_article')
        ->alias('a')
        ->join('bg_cate c','c.cate_id = a.cate_id')
        ->where("a.article_status ='open' and (a.article_title like '%{$searchName}%')")
        ->order('id','desc')
        ->limit(20)
        ->select();

      $this->assign('serachName',$searchName);
      $this->assign('dataTotal',20);  //默认搜索20条
      $this->assign('articleList',$articleList);
      //获取栏目
      $cate = new CateModel();
      $cateList = $cate->order('cate_id','asc')->select();
      $this->assign('cateList',$cateList);

      //获取所有标签
      $tag = new TagModel;
      $tagList = $tag->where('status = 1')->select();
      $this->assign('tagList',$tagList);

      //获取最新添加的前5条文章
      $article = new ArticleModel;
      $articleListMsg = $article->where("article_status = 'open'")->order('id','desc')->limit(5)->select();
      $this->assign('articleListMsg',$articleListMsg);

      return $this->view->fetch();
    }
    //加载更多搜索文章
    public function more_search_list()
    {
      $data = Request::instance()->post();
      if($data)
      {
        //var_dump($data);
        //exit;
        $size = $data['total'];
        $page = $data['paged'];
        $searchName = $data['searchName'];
        $offset = $page * $size;
        //获取更多文章列表
        $articleList = Db::table('bg_article')
          ->alias('a')
          ->join('bg_cate c','c.cate_id = a.cate_id')
          ->where("a.article_status ='open' and (a.article_title like '%{$searchName}%')")
          ->order('id','desc')
          ->limit($offset,$size)
          ->select();

        //echo   Db::getLastSql();
        //var_dump($articleList);
        //exit;
        if(!empty($articleList))
        {
          $articleListText = '';
          foreach ($articleList as $key => $value) {


            //内容截取
            $articleContent = mb_substr($value['article_content'],0,175);
            //var_dump($articleContent);
            //exit;
            $action = 'index/article_list';
            $id = 'id';
            //内容过滤
            $articleContent = str_replace(['#','<pre>','<pre'],'',$articleContent);
            $articleId = $value['id'];

            //$article_details_url =  "{:url('{$action}',['{$id}'=>{$articleId}])}";
            $article_details_url =  "index/index/article_details/id$articleId.html";
            //echo $article_details_url;
            //exit;
            $articleListText .= '<div class="ajax-load-con content wow bounceInUp" style="visibility: visible; animation-name: bounceInUp;">
      								<div class="content-box posts-gallery-box">
      									<div class="posts-gallery-content"> '.
      										'<h2><a href='.'"'.$article_details_url.'"'." title='{$value['article_title']}'>{$value['article_title']}</a></h2> ".

      										'<div class="posts-gallery-text">'.$articleContent.'</div>
      										<div class="posts-default-info posts-gallery-info">
      											<ul> '.
      												"<li class='ico-cat'><i class='icon-list-2'></i> <a href=''>{$value['cate_name']}</a></li>
      												<li class='ico-time'><i class='icon-clock-1'></i> {$value['article_date']}</li>
      												<li class='ico-eye'><i class='icon-eye-4'></i>{$value['article_views']}</li>
      												<li class='ico-like'><i class='icon-heart'></i> {$value['article_like']}</li> ".
      											'</ul>
      										</div>
      									</div>
      								</div>
      							</div>';
                    //echo $text;
                    //exit;
          }
        }
        else {
          $articleListText = 0;
        }
      }
      $articleData = [
        'code'=>200,
        'msg' =>'成功',
        'next' => $page + 1,
        'total' => $offset + 2,
        'postlist' => $articleListText

      ];
      return json($articleData);
    }
    //通过标签搜索
    public function tag_search_list($searchTagId)
    {
      // $request = Request::instance();
      //
      // $searchName = $request->get('searchTag');

      // $article = new ArticleModel;
      // $articleList = $article->where('article_title&article_content','like',"%$searchName%")
      // ->order('id','desc')
      // ->select();
      // var_dump($articleList);
      // $this->assign('articleList',$articleList);

      //链式操作联合查询
      $articleList = Db::table('bg_article')
        ->alias('a')
        ->join('bg_cate c','c.cate_id = a.cate_id')
        ->join('bg_article_for_tag aft','aft.article_id = a.id')
        ->join('bg_tag t','t.id = aft.tag_id')
        ->where("a.article_status ='open' and t.id = '{$searchTagId}'")
        ->order('t.id','desc')
        ->limit(20)
        ->select();
        // var_dump($articleList);
      $this->assign('tagId',$searchTagId);
      $this->assign('dataTotal',20);  //默认搜索20条
      $this->assign('articleList',$articleList);
      //获取栏目
      $cate = new CateModel();
      $cateList = $cate->order('cate_id','asc')->select();
      $this->assign('cateList',$cateList);

      //获取所有标签
      $tag = new TagModel;
      $tagList = $tag->where('status = 1')->select();
      $this->assign('tagList',$tagList);

      //获取最新添加的前5条文章
      $article = new ArticleModel;
      $articleListMsg = $article->where("article_status = 'open'")->order('id','desc')->limit(5)->select();
      $this->assign('articleListMsg',$articleListMsg);

      return $this->view->fetch();
    }

    //加载更多标签搜索文章
    public function more_tag_search_list()
    {
      $data = Request::instance()->post();
      if($data)
      {
        //var_dump($data);
        //exit;
        $size = $data['total'];
        $page = $data['paged'];
        $searchTagId = $data['tagId'];
        $offset = $page * $size;
        //获取更多文章列表
        $articleList = Db::table('bg_article')
          ->alias('a')
          ->join('bg_cate c','c.cate_id = a.cate_id')
          ->join('bg_article_for_tag aft','aft.article_id = a.id')
          ->join('bg_tag t','t.id = aft.tag_id')
          ->where("a.article_status ='open' and t.id = '{$searchTagId}'")
          ->order('t.id','desc')
          ->limit($offset,$size)
          ->select();

        //echo   Db::getLastSql();
        //var_dump($articleList);
        //exit;
        if(!empty($articleList))
        {
          $articleListText = '';
          foreach ($articleList as $key => $value) {


            //内容截取
            $articleContent = mb_substr($value['article_content'],0,175);
            //var_dump($articleContent);
            //exit;
            $action = 'index/article_list';
            $id = 'id';
            //内容过滤
            $articleContent = str_replace(['#','<pre>','<pre'],'',$articleContent);
            $articleId = $value['id'];

            //$article_details_url =  "{:url('{$action}',['{$id}'=>{$articleId}])}";
            $article_details_url =  "index/index/article_details/id$articleId.html";
            //echo $article_details_url;
            //exit;
            $articleListText .= '<div class="ajax-load-con content wow bounceInUp" style="visibility: visible; animation-name: bounceInUp;">
      								<div class="content-box posts-gallery-box">
      									<div class="posts-gallery-content"> '.
      										'<h2><a href='.'"'.$article_details_url.'"'." title='{$value['article_title']}'>{$value['article_title']}</a></h2> ".

      										'<div class="posts-gallery-text">'.$articleContent.'</div>
      										<div class="posts-default-info posts-gallery-info">
      											<ul> '.
      												"<li class='ico-cat'><i class='icon-list-2'></i> <a href=''>{$value['cate_name']}</a></li>
      												<li class='ico-time'><i class='icon-clock-1'></i> {$value['article_date']}</li>
      												<li class='ico-eye'><i class='icon-eye-4'></i>{$value['article_views']}</li>
      												<li class='ico-like'><i class='icon-heart'></i> {$value['article_like']}</li> ".
      											'</ul>
      										</div>
      									</div>
      								</div>
      							</div>';
                    //echo $text;
                    //exit;
          }
        }
        else {
          $articleListText = 0;
        }
      }
      $articleData = [
        'code'=>200,
        'msg' =>'成功',
        'next' => $page + 1,
        'total' => $offset + 2,
        'postlist' => $articleListText

      ];
      return json($articleData);
    }
}
