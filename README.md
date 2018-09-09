# Phalcon Base Project

This is a base project to be used when starting other projects based on [PhalconPHP](https://phalconphp.com/).

The project base includes:
- Standard MVC structure
- Prophiler
- Redis session storage
- Debug mode control
- Complete Vagrant bootstrap
- Ready Auth control with permission management
- Multilingual support
- Logger

## Debug mode
To use debug mode in your dev environment create a .debug file in project root:
```
touch .debug
```

## Database schema
Database schema is stored in /schemas/ folder. It is loaded into Vagrant's MySQL database when provisioning.

## Running locally
Vagrant is preset to run a local instance at 192.168.50.10. Add the following line to your hosts file to be able to access it:
```
192.168.50.10 static.phalcon-project.test www.phalcon-project.test
```
#### Working pages:
```
http://www.phalcon-project.test/
http://www.phalcon-project.test/register
http://www.phalcon-project.test/login
http://www.phalcon-project.test/logout
http://www.phalcon-project.test/user - Accessible only with ROLE_USER or ROLE_ADMIN
http://www.phalcon-project.test/admin - Accessible only with ROLE_ADMIN
```

## Redis admin
To be able to access Redis admin add the following line to the hosts file:
```
192.168.50.10 redis-admin.local
```

## Routing and translations
The translations are integrated with the router, allowing the routes themselves to be translated.
Translation files are stored in `config/translations/{lang}.php` as arrays.
The translation file is loaded and available through the DI on the beginning.
It consists of 3 main keys:
- Lang
  - Holds just the name of the lang, if it is needed to double-check what will be printed
- Routes
  - Holds the translations for the app's routes. Translations are arranged in 'route-name' => '/translated/route' fashion.
- Content
  - Contains the content translations and is loaded into the Phalcon's translation service on the first use.

It is not necessary to translate every route. Defaults will be used for all non-translated routes. The route definition is in `config/routes.php`.

## Planned for the future:
- CLI