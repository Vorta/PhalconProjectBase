#!/usr/bin/env bash

MYSQL_PASS="root"

MYSQL_DB_NAME="phalcon_project"
MYSQL_DB_USER="phalcon_project_user"
MYSQL_DB_PASS="Ruc=\$6&AneJ@WAze?aS6eNEprUF3#Tas"

# Setup server time
sudo unlink /etc/localtime
sudo ln -s /usr/share/zoneinfo/Europe/Zagreb /etc/localtime

sudo apt-get update
sudo apt-get upgrade

sudo apt-get install -y debconf-util
sudo apt-get install dirmngr
sudo apt-get install -y aptitude

sudo aptitude update -y
sudo aptitude safe-upgrade -y

sudo sed -i "s/#alias ll='.*'/alias ll='ls -al'/g" /home/vagrant/.bashrc

sudo aptitude install -y curl
sudo aptitude install -y apt-transport-https

# Nginx
wget -O /tmp/RPM-GPG-KEY-nginx http://nginx.org/keys/nginx_signing.key
sudo apt-key add /tmp/RPM-GPG-KEY-nginx
echo "deb http://nginx.org/packages/mainline/debian/ stretch nginx" | sudo tee /etc/apt/sources.list.d/nginx.list

sudo aptitude update -y
sudo aptitude install -y nginx

# Configure Nginx
sudo sed -i 's/sendfile on;/sendfile off;/' /etc/nginx/nginx.conf
sudo sed -i "s/user  nginx;/user vagrant;/" /etc/nginx/nginx.conf

usermod -a -G www-data vagrant

# MySQL 5.7
wget -O /tmp/RPM-GPG-KEY-mysql https://repo.mysql.com/RPM-GPG-KEY-mysql
sudo apt-key add /tmp/RPM-GPG-KEY-mysql
echo "deb http://repo.mysql.com/apt/debian/ stretch mysql-5.7" | sudo tee /etc/apt/sources.list.d/mysql.list

sudo aptitude update -y

sudo debconf-set-selections <<< "mysql-community-server mysql-community-server/root-pass password $MYSQL_PASS"
sudo debconf-set-selections <<< "mysql-community-server mysql-community-server/re-root-pass password $MYSQL_PASS"

sudo aptitude install -y mysql-server
echo "alias db='mysql -u root --password=$MYSQL_PASS'" >> /home/vagrant/.bashrc

mysql -u root --password=$MYSQL_PASS -e "CREATE SCHEMA IF NOT EXISTS $MYSQL_DB_NAME;"
mysql -u root --password=$MYSQL_PASS -e "GRANT ALL PRIVILEGES ON $MYSQL_DB_NAME.* TO '$MYSQL_DB_USER'@'localhost' IDENTIFIED BY '$MYSQL_DB_PASS';"
mysql -u $MYSQL_DB_USER --password=$MYSQL_DB_PASS $MYSQL_DB_NAME < /vagrant/schemas/database.sql

# PHP 7.2
sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
echo 'deb https://packages.sury.org/php/ stretch main' | sudo tee -a /etc/apt/sources.list

sudo aptitude update -y
sudo aptitude install -y php7.2-fpm php7.2-mysql php7.2-curl php7.2-dev php7.2-json php7.2-zip php7.2-mbstring

# Configure PHP
sudo sed -i.bak 's/^;cgi.fix_pathinfo.*$/cgi.fix_pathinfo = 1/g' /etc/php/7.2/fpm/php.ini

sudo sed -i "s/user = www-data/user = vagrant/" /etc/php/7.2/fpm/pool.d/www.conf
sudo sed -i "s/group = www-data/group = vagrant/" /etc/php/7.2/fpm/pool.d/www.conf
sudo sed -i "s/listen\.owner.*/listen.owner = vagrant/" /etc/php/7.2/fpm/pool.d/www.conf
sudo sed -i "s/listen\.group.*/listen.group = vagrant/" /etc/php/7.2/fpm/pool.d/www.conf
sudo sed -i "s/.*listen\.mode.*/listen.mode = 0666/" /etc/php/7.2/fpm/pool.d/www.conf

# xDebug
sudo aptitude install -y php-xdebug
sudo aptitude install -y libpng-dev

echo "-- Configure xDebug (idekey = PHP_STORM) --"
sudo tee -a /etc/php/7.2/mods-available/xdebug.ini << END
xdebug.remote_enable=1
xdebug.remote_connect_back=1
xdebug.remote_port=9001
xdebug.idekey=PHP_STORM
END

# Git
sudo aptitude install -y git

# PhalconPHP
git clone --depth=1 "git://github.com/phalcon/cphalcon.git"
cd cphalcon/build
sudo ./install
cd

sudo tee -a /etc/php/7.2/mods-available/phalcon.ini << END
; configuration for php common module
; priority=20
extension=phalcon.so
END

sudo ln -s /etc/php/7.2/mods-available/phalcon.ini /etc/php/7.2/fpm/conf.d/20-phalcon.ini
sudo ln -s /etc/php/7.2/mods-available/phalcon.ini /etc/php/7.2/cli/conf.d/20-phalcon.ini

# Redis
sudo aptitude install -y redis-server

sudo sed -i "s/appendonly no/appendonly yes/" /etc/redis/redis.conf
sudo sed -i "s/dbfilename dump\.rdb/dbfilename redis.rdb/" /etc/redis/redis.conf

sudo aptitude install -y php-redis

# Redis admin
cd
git clone https://github.com/ErikDubbelboer/phpRedisAdmin.git
cd phpRedisAdmin
git clone https://github.com/nrk/predis.git vendor
cd
sudo chmod -R 775 phpRedisAdmin

# Composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

# Configure host
sudo rm /etc/nginx/conf.d/default.conf

sudo touch /etc/nginx/conf.d/project.conf
sudo tee -a /etc/nginx/conf.d/project.conf << END
server {

    listen 80;
    server_name www.phalcon-project.test;

    index index.php index.html;

    set \$root_path     /vagrant/public;
    set \$static_path   /vagrant/static;
    root                \$root_path;

    charset utf-8;
    autoindex off;

    access_log /var/log/nginx/project_access.log;
    error_log /var/log/nginx/project_error.log;
    rewrite_log on;

    location @rewrite {
        rewrite ^/(.*)\$ /index.php?_url=/\$1;
    }

    try_files \$uri \$uri/ @rewrite;

    # Remove trailing slash to please routing system.
    if (!-d \$request_filename) {
        rewrite ^/(.+)/\$ /\$1 permanent;
    }

    # PHP FPM configuration.
    location ~ ^/index\.php(/|\$) {
        fastcgi_pass unix:/run/php/php7.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_split_path_info ^(.+\.php)(.*)\$;

        include /etc/nginx/fastcgi_params;

        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT \$realpath_root;

        fastcgi_read_timeout 300;
    }

    location = /favicon.ico {
        root \$static_path;
        rewrite favicon.ico /assets/browser_icons/favicon.ico;
        break;
    }

    location = /apple-touch-icon-precomposed.png {
        root \$static_path;
        rewrite apple-touch-icon-precomposed.png /assets/browser_icons/apple-touch-icon-precomposed.png;
        break;
    }

    location = /apple-touch-icon.png {
        root \$static_path;
        rewrite apple-touch-icon.png /assets/browser_icons/apple-touch-icon.png;
        break;
    }

    location = /robots.txt {
        root \$static_path;
        #rewrite robots.txt /robots.txt;
        break;
    }

}
END

sudo touch /etc/nginx/conf.d/project_static.conf
sudo tee -a /etc/nginx/conf.d/project_static.conf << END
server {

    listen          80;
    server_name     static.phalcon-project.test;

    root            /vagrant/static;

    charset utf-8;
    autoindex off;

    access_log /var/log/nginx/project_static_access.log;
    error_log /var/log/nginx/project_static_error.log;
    rewrite_log on;

    add_header 'Access-Control-Allow-Origin' '*';
    add_header 'Access-Control-Allow-Methods' 'GET';

}
END

sudo touch /etc/nginx/conf.d/redis-admin.conf
sudo tee -a /etc/nginx/conf.d/redis-admin.conf << END
server {

    listen 80;
    server_name redis-admin.local;

    index index.php index.html;

    root    /home/vagrant/phpRedisAdmin;

    charset utf-8;
    autoindex off;

    access_log /var/log/nginx/redisadmin_access.log;
    error_log /var/log/nginx/redisadmin_error.log;
    rewrite_log on;

    location @rewrite {
        rewrite ^/(.*)\$ /index.php?_url=/\$1;
    }

    try_files \$uri \$uri/ @rewrite;

    # Remove trailing slash to please routing system.
    if (!-d \$request_filename) {
        rewrite ^/(.+)/\$ /\$1 permanent;
    }

    # PHP FPM configuration.
    location ~ \.php\$ {
        fastcgi_pass unix:/run/php/php7.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_split_path_info ^(.+\.php)(.*)\$;

        include /etc/nginx/fastcgi_params;

        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT \$realpath_root;

        fastcgi_read_timeout 300;
    }
}
END

echo "cd /vagrant" >> /home/vagrant/.bashrc

# Restart servers
sudo service nginx restart
sudo service php7.2-fpm restart