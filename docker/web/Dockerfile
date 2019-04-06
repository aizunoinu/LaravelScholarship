FROM centos:6.6
MAINTAINER Junya Sato <s1200015junya@gmail.com>

# TimeZoneの設定
RUN echo 'ZONE"=Asia/Tokyo"' > /etc/sysconfig/clock

# yumアップデート
RUN yum -y update

# apacheのインストール
RUN yum install -y httpd ; yum clean all

# apacheの設定
RUN rm -rf /etc/httpd/conf.d/welcome.conf
RUN sed -ri '/<Directory "\/var\/www\/html">/,/<\/Directory>/s/    AllowOverride None/    AllowOverride All/' /etc/httpd/conf/httpd.conf && \
    sed -ri '/<Directory "\/var\/www\/html">/,/<\/Directory>/s/    Options Indexes FollowSymLinks/    Options Indexes FollowSymLinks Includes/' /etc/httpd/conf/httpd.conf && \
    sed -ri 's/DocumentRoot "\/var\/www\/html"/DocumentRoot "\/var\/www\/html\/htdocs"/g' /etc/httpd/conf/httpd.conf && \
    sed -ri 's/DirectoryIndex index.html index.html.var/DirectoryIndex index.html index.shtml index.html.var/' /etc/httpd/conf/httpd.conf
RUN echo -e 'RewriteEngine on\nRewriteCond $1 !^/(index\.php|images|js|css|fonts|less|lib|scss|robots\.txt|favicon\.ico)\nRewriteRule ^(.*)$ /index.php/$1 [L]' >> /etc/httpd/conf/httpd.conf

# remiリポジトリの登録
RUN rpm -Uvh http://ftp.jaist.ac.jp/pub/Linux/Fedora/epel/6/x86_64/epel-release-6-8.noarch.rpm \
  && rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm

# wgetのインストール
RUN yum install -y wget ; yum clean all

# php 5.6 のインストール
RUN yum install -y --enablerepo=remi --enablerepo=remi-php56 php php-opcache php-devel \
  php-mbstring php-mcrypt php-mysqlnd php-phpunit-PHPUnit php-pecl-xdebug \
  php-pecl-xhprof php-gd php-pdo php-pgsql php-xml; \
  yum clean all

# phpの設定
RUN sed -ri 's/;date.timezone =/date.timezone = Asia\/Tokyo/' /etc/php.ini && \
    sed -ri 's/display_errors = Off/display_errors = On/' /etc/php.ini && \
    sed -ri 's/post_max_size = 8M/post_max_size = 100M/' /etc/php.ini && \
    sed -ri 's/upload_max_filesize = 2M/upload_max_filesize = 100M/' /etc/php.ini && \
    sed -ri 's/;mbstring.language = Japanese/mbstring.language = Japanese/' /etc/php.ini && \
    sed -ri 's/;mbstring.internal_encoding = EUC-JP/mbstring.internal_encoding = UTF-8/' /etc/php.ini && \
    sed -ri 's/;mbstring.http_input = auto/mbstring.http_input = pass/' /etc/php.ini && \
    sed -ri 's/;mbstring.http_output = SJIS/mbstring.http_output = pass/' /etc/php.ini && \
    sed -ri 's/;mbstring.encoding_translation = Off/mbstring.encoding_translation = Off/' /etc/php.ini

RUN yum install -y postfix crontabs supervisor ; yum clean all

RUN rm /var/lib/rpm/__db.00*
RUN rpm --rebuilddb; yum clean all

COPY etc/php.d/xdebug.ini /etc/php.d/xdebug.ini
COPY etc/postfix/main.cf /etc/postfix/main.cf
COPY etc/supervisord.conf /etc/supervisord.conf
COPY etc/crontab /etc/crontab

RUN export CI_ENV="development"
WORKDIR /var/www/html
EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
