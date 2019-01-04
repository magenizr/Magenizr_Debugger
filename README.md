# Magenizr Debugger
This Magento 2 module allows developers to view the latest report file from `./var/report/` or download the entire log and report folder as a tar file. 

![Magenizr Debugger - Intro](http://download.magenizr.com/pub/magenizr_debugger/all/intro.gif)

![Magenizr Debugger - Backend](http://download.magenizr.com/pub/magenizr_debugger/all/backend/01.gif)

![Magenizr Debugger - Backend](http://download.magenizr.com/pub/magenizr_debugger/all/backend/02.gif)

## Purchase
This module is available for free on [GitHub](https://github.com/magenizr). For warranty and support, please purchase the module on https://shop.magenizr.com.

## System Requirements
* Magento 2.1.x, 2.2.x, 2.3.x
* PHP 5.x, 7.x

## Installation (Composer)

1. Add this extension to your repository `composer config repositories.magenizr/magento2-debugger git https://github.com/magenizr/Magenizr_Debugger.git`
2. Update your composer.json `composer require "magenizr/magento2-debugger":"1.0.0"`

```
./composer.json has been updated
Loading composer repositories with package information
Updating dependencies (including require-dev)              
Package operations: 1 install, 0 updates, 0 removals
  - Installing magenizr/magento2-debugger (1.0.0): Downloading (100%)         
Writing lock file
Generating autoload files
```

3. Enable the module and clear static content.

```
php bin/magento module:enable Magenizr_Debugger --clear-static-content
php bin/magento setup:upgrade
```

## Installation (Manually)
1. Download the latest version of the source code.
2. Extract the downloaded tar.gz file. Example: `tar -xzf Magenizr_Debugger_1.0.0.tar.gz`.
3. Copy the code into `./app/code/Magenizr/Debugger/`.
4. Enable the module and clear static content.

```
php bin/magento module:enable Magenizr_Debugger --clear-static-content
php bin/magento setup:upgrade
```

## Features
* The functionality can be restricted to specific roles via `System > Permissions > User Roles`. The ACL resource is `System > Tools > Debugger`. Beside that, it can be restricted to one or multiple usernames or IP addresses.
* The configuration can be found in `Stores > Configuration > Advanced > Developer > Debugger`. All features are enabled by default.
* Display the latest report file from `./var/report/` directly on the dashboard.
* Download all files from `./var/log/` or `./var/report/` as a tar file.
* Restrict access to specific IP addresses.

## Usage
The `Dashboard` of the module can be found in the backend section `Stores > Tools > Debugger`.

## Support
If you have any issues with this extension, open an issue on [Github](https://github.com/magenizr/Magenizr_Debugger/issues). For a custom build, don't hesitate to contact us on [Magento Marketplace](https://marketplace.magento.com/partner/magenizr).

## Roadmap
* Display all information from phpinfo()
* Display available disk space

## Contact
Follow us on [GitHub](https://github.com/magenizr), [Twitter](https://twitter.com/magenizr) and [Facebook](https://www.facebook.com/magenizr).

## History
===== 1.0.1 =====
* Admin Resource in admin controller refactored
* Date and time for report file added

===== 1.0.0 =====
* Stable version

## License
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)