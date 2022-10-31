# Debugger
This Magento 2 module allows developers to view the latest report file from `./var/report/` or download the entire log and report folder as a tar file.

![Magenizr Debugger - Backend](https://images2.imgbox.com/d1/de/jzLYOdv0_o.png)

## Purchase
This module is available for free on [GitHub](https://github.com/magenizr). For warranty and support, you can purchase the module on https://shop.magenizr.com.

## System Requirements
* Magento 2.3.x, 2.4.x
* PHP 5.x, 7.x

## Installation (Composer)

1. Update your composer.json `composer require "magenizr/magento2-debugger":"1.0.2" --no-update`
2. Install dependencies and update your composer.lock `composer update magenizr/magento2-debugger --lock`

```
./composer.json has been updated
Loading composer repositories with package information
Updating dependencies (including require-dev)              
Package operations: 1 install, 0 updates, 0 removals
  - Installing magenizr/magento2-debugger (1.0.2): Downloading (100%)         
Writing lock file
Generating autoload files
```

3. Enable the module and clear static content.

```
php bin/magento module:enable Magenizr_Debugger --clear-static-content
```

## Installation (Composer 2)

1. Update your composer.json `composer require "magenizr/magento2-debugger":"1.0.2" --no-update`
2. Use `composer update magenizr/magento2-debugger --no-install` to update your composer.lock file.

```
Updating dependencies
Lock file operations: 1 install, 1 update, 0 removals
  - Locking magenizr/magento2-adminbranding (1.0.1)
```

3. And then `composer install` to install the package.

```
Installing dependencies from lock file (including require-dev)
Verifying lock file contents can be installed on current platform.
Package operations: 1 install, 0 update, 0 removals
  - Installing magenizr/magento2-adminbranding (1.0.1): Extracting archive
```

4. Enable the module and clear static content.

```
php bin/magento module:enable Magenizr_AdminBranding --clear-static-content
```

## Installation (Manually)
1. Download the latest version of the source code.
2. Extract the downloaded tar.gz file. Example: `tar -xzf Magenizr_Debugger_1.0.2.tar.gz`.
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
If you experience any issues, don't hesitate to open an issue on [Github](https://github.com/magenizr/Magenizr_Debugger/issues). For a custom build, don't hesitate to contact us on [Magento Marketplace](https://marketplace.magento.com/partner/magenizr).

## Roadmap
* Display all information from phpinfo()
* Display available disk space

## Contact
Follow us on [GitHub](https://github.com/magenizr), [Twitter](https://twitter.com/magenizr) and [Facebook](https://www.facebook.com/magenizr).

## History
===== 1.0.2 =====
* Cleanup various files to meet coding standards (EQP, ECG)
* Framework constraint removed from `composer.json`

===== 1.0.1 =====
* Admin Resource in admin controller refactored
* Date and time for report file added

===== 1.0.0 =====
* Stable version

## License
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)
