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

## Planned for the future:
- CLI