# Phalcon Base Project

This is a base project to be used when starting other projects based on [PhalconPHP](https://phalconphp.com/).

The project base includes:
- Standard MVC structure
- Prophiler
- Redis session storage
- Debug mode control
- Complete Vagrant bootstrap
- Ready Auth control with permission management
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

## Planned for the future:
- CLI