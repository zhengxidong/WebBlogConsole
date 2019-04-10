# WebBlogConsole
博客后台管理

## 部署

切换到home目录
> cd /home

克隆项目
> git clone https://github.com/zhengxidong/WebBlog.git

给上传图片目录建立软连接
ln -s 源目录 目标目录
> ln -s /home/WebBlog/public/static/uploads /home/data/uploads

配置容器内nginx，重写地址
```js
location / {

     if (!-e $request_filename) {
         rewrite  ^(.*)$  /index.php?s=/$1  last;
         break;
     }
}
```

## 注意事项

访问站点出现以下错误

```js
file_put_contents(/usr/local/nginx/html/blog/runtime/temp/f8b69fbc1e64f49a2a62a21dd941bfea.php): failed to open stream: Permission denied
```

解决方法：

赋予runtime目录下执行权限

> chmod 777 -R runtime
