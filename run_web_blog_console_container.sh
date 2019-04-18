#!/bin/bash

#停止容器
echo "停止容器"
docker stop web-blog-console
#删除容器
echo "删除容器"
docker rm web-blog-console
#删除镜像
#docker rm web-blog-console

#重build镜像
#./build_web_blog_console_image.sh

# 切换目录
#cd ..

# 更新
echo "更新代码"
git pull

# 实例化容器
docker run -d --restart=always -p 7070:80 --name web-blog-console \
	-v /home/www/WebBlogConsole/application:/usr/local/nginx/html/console.itellyou.site/application \
	-v /home/www/WebBlogConsole/extend:/usr/local/nginx/html/console.itellyou.site/extend \
	-v /home/www/WebBlogConsole/public:/usr/local/nginx/html/console.itellyou.site/public \
	-v /home/www/WebBlogConsole/thinkphp:/usr/local/nginx/html/console.itellyou.site/thinkphp \
	-v /home/www/WebBlogConsole/runtime:/usr/local/nginx/html/console.itellyou.site/runtime \
	-v /home/www/WebBlogConsole/vendor:/usr/local/nginx/html/console.itellyou.site/vendor \
	-v /home/www/WebBlogConsole/etc/nginx/conf.d/console.itellyou.site.conf:/etc/nginx/conf.d/console.itellyou.site.conf \
        -v /home/data/uploads:/usr/local/nginx/html/console.itellyou.site/public/static/uploads \
        -v /home/www/config/database.php:/usr/local/nginx/html/console.itellyou.site/application/database.php \
	blog
