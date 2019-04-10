<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace think;
//define('APP_PATH', __DIR__ . '/../app/');
// ThinkPHP 引导文件
// 1. 加载基础文件
require __DIR__ . '/base.php';

//开启域名部署后

// switch ($_SERVER['HTTP_HOST']) {
//
//     case 'www.blog.com':
//
//         $model = 'index';// home模块
//
//         $route = true;// 开启路由
//
//         break;
//
//     case 'gohosts.com':
//
//         $model = 'manage';// admin模块
//
//         $route = false;// 关闭路由
//
//         break;
//
// }
//
// \think\Route::bind($model);// 绑定当前入口文件到模块
// \think\App::route($route);// 路由


// 2. 执行应用
App::run()->send();
