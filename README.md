# Extended Magento 2 Module Contact

Extended functional for default Magento 2 Contact module.

## Install

#### Composer

This package can be installed as a [Composer](https://getcomposer.org/) dependency [bogkov/magento2-module-contact](https://packagist.org/packages/bogkov/magento2-module-contact).

```bash
composer require bogkov/magento2-module-contact
```

#### Enable Module

```php
php bin/magento module:enable Bogkov_Contact
php bin/magento setup:upgrade
php bin/magento setup:di:compile
```

You may need to Flush Magento Cache after installation.

## Roadmap

### 1.0

 - [x] Core structure
 - [x] Setup InstallSchema and Uninstall
 - [x] ACL
 - [x] Routes
 - [x] Admin menu

### 1.1

 - [x] Model: resource model, grid collection 
 - [x] Controller: admin index action index
 - [x] View: admin layout for admin controller index action index
 - [x] View: admin UI component contact grid
 - [x] View: contact grid actions

### 1.2

 - [x] I18n: Core
 - [x] Controller: admin index action view
 - [x] View: admin layout for admin controller index action view
 - [x] Controller: override default Magento contact action post
 - [X] View: contact grid action delete
 
### 1.3

 - [X] Controller: admin index action delete
 - [X] View: admin grid manual change status
 - [X] Controller: admin manual change status
 - [X] Controller: admin index action response
 - [x] Admin response email template and sent