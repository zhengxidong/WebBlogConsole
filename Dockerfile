FROM blog

COPY ./application /usr/local/nginx/html/console.itellyou.site/application
COPY ./extend /usr/local/nginx/html/console.itellyou.site/extend
COPY ./public /usr/local/nginx/html/console.itellyou.site/public
COPY ./runtime /usr/local/nginx/html/console.itellyou.site/runtime
COPY ./thinkphp /usr/local/nginx/html/console.itellyou.site/thinkphp
COPY ./vendor /usr/local/nginx/html/console.itellyou.site/vendor
COPY ./etc/nginx/conf.d/console.itellyou.site.conf /etc/nginx/conf.d/console.itellyou.site.conf

EXPOSE 80
CMD ["/usr/bin/supervisord"]
 
