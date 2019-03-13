# Magento Translation Extension by [Magefan](https://magefan.com/magento2-extensions)

This module allows to make translation in admin panel (Sysmtem > Translation by Magefan)

## Requirements
  * Magento Community Edition 2.1.x-2.3.x or Magento Enterprise Edition 2.1.x-2.3.x

## Installation Method 1 - Installing via composer
  * Open command line
  * Using command "cd" navigate to your magento2 root directory
  * Run the commands:
  
```
composer require magefan/module-translation
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

## Installation Method 2 - Installing via FTP using archive
  * Download [ZIP Archive](https://github.com/magefan/module-translation/archive/master.zip)
  * Extract files
  * In your Magento 2 root directory create folder app/code/Magefan/Translation
  * Copy files and folders from archive to that folder
  * In command line, using "cd", navigate to your Magento 2 root directory
  * Run the commands:
```
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

## Support
If you have any issues, please [contact us](mailto:support@magefan.com)
then if you still need help, open a bug report in GitHub's
[issue tracker](https://github.com/magefan/module-translation/issues).

## License
The code is licensed under [EULA](https://magefan.com/end-user-license-agreement).

## Other Magefan Extensions That Can Be Installed Via Composer
  * [Magento 2 Blog Extension](https://magefan.com/magento2-blog-extension)
  * [Magento 2 Auto Currency Switcher Extension](https://magefan.com/magento-2-currency-switcher-auto-currency-by-country)
  * [Magento 2 Login As Customer Extension](https://magefan.com/login-as-customer-magento-2-extension)
  * [Magento 2 Conflict Detector Extension](https://magefan.com/magento2-conflict-detector)
  * [Magento 2 Lazy Load Extension](https://github.com/magefan/module-lazyload)
  * [Magento 2 Rocket JavaScript Extension](https://magefan.com/rocket-javascript-deferred-javascript)
  * [Magento 2 CLI Extension](https://magefan.com/magento2-cli-extension)
  
