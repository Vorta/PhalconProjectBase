# Phalcon Base Project

This is a base project to be used when starting other projects based on [Phalcon Framework](https://phalconphp.com/).

It is structured for multi-module setup. Basic user registration & login included.

## Requirements
The project is tested with and requires:
- Nginx 1.15 (I'm providing Nginx config, however the app is configurable to work on Apache)
- PHP 7.3
  - [PhalconPHP 3.4](https://phalconphp.com/) (planned upgrade to 4.0 on its release)
  - [PHP-DS](https://pecl.php.net/package/ds) (not used in the base project; however, I end up using it in most of my projects)
  - [PHP-PSR](https://pecl.php.net/package/psr) (will be required for PhalconPHP 4.0)
  - [YAML](https://pecl.php.net/package/yaml)
- MySQL 8.0
- Redis 5

## Extension installation (Ubuntu/Debian)

#### PHP DS
```bash
sudo pecl channel-update pecl.php.net
sudo pecl install ds

sudo tee -a /etc/php/7.3/mods-available/ds.ini << END
extension=ds.so
END
sudo ln -s /etc/php/7.3/mods-available/ds.ini /etc/php/7.3/fpm/conf.d/20-ds.ini
sudo ln -s /etc/php/7.3/mods-available/ds.ini /etc/php/7.3/cli/conf.d/20-ds.ini
```

#### YAML
```bash
sudo apt install libyaml-dev
sudo pecl channel-update pecl.php.net
sudo pecl install yaml

sudo tee -a /etc/php/7.3/mods-available/yaml.ini << END
extension=yaml.so
END
sudo ln -s /etc/php/7.3/mods-available/yaml.ini /etc/php/7.3/fpm/conf.d/20-yaml.ini
sudo ln -s /etc/php/7.3/mods-available/yaml.ini /etc/php/7.3/cli/conf.d/20-yaml.ini
```

#### PhalconPHP v3.4
```bash
git clone --branch v3.4.3 https://github.com/phalcon/cphalcon.git
cd cphalcon/build
sudo ./install

sudo tee -a /etc/php/7.3/mods-available/phalcon.ini << END
extension=phalcon.so
END

sudo ln -s /etc/php/7.3/mods-available/phalcon.ini /etc/php/7.3/fpm/conf.d/20-phalcon.ini
sudo ln -s /etc/php/7.3/mods-available/phalcon.ini /etc/php/7.3/cli/conf.d/20-phalcon.ini
```

## Nginx config
This is the config I use, feel free to modify to fit your requirements.
Don't forget to change the `$root_path` and `$static_path`.

```
# phalcon-project.conf
server {
    listen 80;
    server_name www.phalcon-project.*;

    index index.php index.html;

    set $root_path /path/to/project/public;
    set $static_path /path/to/project/static;
    root $root_path;

    charset utf-8;
    autoindex off;

    access_log /var/log/nginx/phalcon-project_access.log;
    error_log /var/log/nginx/phalcon-project_error.log;
    rewrite_log on;

    try_files $uri $uri/ /index.php?_url=$uri&$args;

    if (!-d $request_filename) {
        rewrite ^/(.+)/$ /$1 permanent;
    }

    # PHP FPM configuration.
    location ~ /index\.php(/|$) {
        fastcgi_pass unix:/run/php/php7.3-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(.*)$;

        include /etc/nginx/fastcgi_params;

        fastcgi_param SCRIPT_FILENAME   $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT     $realpath_root;
        fastcgi_param SCRIPT_NAME       index.php;
    }

    location = /favicon.ico {
        root $static_path;
        rewrite favicon.ico /static/browser/favicon.ico;
        break;
    }

    location = /apple-touch-icon-precomposed.png {
        root $static_path;
        rewrite apple-touch-icon-precomposed.png /static/browser/apple-touch-icon-precomposed.png;
        break;
    }

    location = /apple-touch-icon.png {
        root $static_path;
        rewrite apple-touch-icon.png /static/browser/apple-touch-icon.png;
        break;
    }

    location = /robots.txt {
        root $static_path;
        break;
    }
}

server {
    listen          80;
    server_name     static.phalcon-project.*;

    root            /path/to/project/static;

    charset utf-8;
    autoindex off;

    access_log /var/log/nginx/phalcon-project-static_access.log;
    error_log /var/log/nginx/phalcon-project-static_error.log;
    rewrite_log on;

    add_header 'Access-Control-Allow-Origin' '*';
    add_header 'Access-Control-Allow-Methods' 'GET';
}
```

## Database
This project is configured for the following DB details:
```
MYSQL_DB_NAME="phalcon_project"
MYSQL_DB_USER="phalcon_project_user"
MYSQL_DB_PASS="Ruc=$6&AneJ@WAze?aS6eNEprUF3#Tas"
```
Database schema is stored in /schemas/ folder.

Recommended charset: `utf8mb4_unicode_520_ci`:

```mysql
CREATE SCHEMA `phalcon_project` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
```

Create the MySQL user and schema first, after that you can initialize it with:
```bash
php bin/console database:initialize
```

If you are making changes in the models and the database, they have to go in the new version of the schema.
To make an empty SQL file that follows the app's naming convention, please use:
```bash
php bin/console database:touch
```
That will create an empty SQL file where you can paste all the changes made in this version.
Others can then apply those changes easily by using:
```bash
php bin/console database:upgrade
```

## Running locally
This base project has a few testing pages available. You can add the following to your hosts file:
```
127.0.0.1 static.phalcon-project.test www.phalcon-project.test www.phalcon-project.hr.test
```
#### Working pages:
```
http://www.phalcon-project.test/en
http://www.phalcon-project.test/en/register
http://www.phalcon-project.test/en/login
http://www.phalcon-project.test/en/logout
```

## Routing and translations
This project base is intended for multilingual web sites with translation system integrated on the route level.
This allows the routes themselves to be translated.
Translation files are stored in `config/translations/{lang}.yaml`.
The translation file is loaded and available through the DI on the startup.
It consists of 3 main keys:
- Lang
  - Holds just the name of the lang, if it is needed to double-check what will be printed
- Routes
  - Holds the translations for the app's routes. Translations are arranged in 'route-name' => '/translated/route' fashion.
- Content
  - Contains the content translations and is loaded into the Phalcon's translation service on the first use.

It is not necessary to translate every route. Defaults will be used for all non-translated routes. The route definition is in `config/routes.yaml`.

### Language hard-coding
If you want a specific language to be used on a certain domain instead of language-defining URI, set a $_SERVER variable LANG to this language.

#### Nginx:
```
    location ~ \.php(/|\$) {
        ...
        fastcgi_param LANG hr;
        ...
    }
```

#### Apache (.htaccess)
```apacheconfig
    SetEnvIf Host \.hr$ LANG=hr
```

## Clearing cache
If you need to clear the cache (e.g. compiled views, cached model metadata) use the following commands:
```bash
php bin/console cache:clear
```