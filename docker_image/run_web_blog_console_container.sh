#!/bin/bash

#停止容器
docker stop web-blog-console
#删除容器
docker rm web-blog-console
#删除镜像
#docker rm web-blog-console

#重build镜像
#./build_web_blog_console_image.sh

# 切换目录
cd ..

# 更新
git pull

# 实例化容器
docker run -d --restart=always -p 7777:80 --name web-blog-console \
	-v ./application:/usr/local/nginx/html/www.itellyou.site/application \
	-v ./extend:/usr/local/nginx/html/www.itellyou.site/extend \
	-v ./public:/usr/local/nginx/html/www.itellyou.site/public \
	-v ./thinkphp:/usr/local/nginx/html/www.itellyou.site/thinkphp \
	-v ./vendor:/usr/local/nginx/html/www.itellyou.site/vendor \
	web-blog-console
