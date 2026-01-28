## Table of Contents
1. [Examples in action](./examples_in_action.md)
2. [Overview of all rules](./all_rectors_overview.md)
3. [Installation](./installation.md)
4. [Configuration and Processing](./configuration_and_processing.md)
5. [Best practice guide](./best_practice_guide.md)
6. [Limitations](./limitations.md)
7. [Contribution](./contribution.md)

# Installation

TYPO3 Rector is a standalone package, that is built on top of `rector/rector` and requires at least PHP 7.4.

```bash
composer require --dev ssch/typo3-rector
```

## Installation below PHP 7.4

```bash
composer require --dev rector/rector:0.13.4
```

This provides rules up to TYPO3 v11

### "Historical" info

Earlier the year 2022 we have been part of the `rector/rector` core.

This changed due to fast pace development, and we split apart again.

This means with version `0.13.5` the rector core does not integrate TYPO3 Rector anymore for faster and more stable development on both ends.
Breaking changes always affect the whole rector ecosystem, causing TYPO3 Rector to be affected by them and slowing down the general development.

That's why we are embedded until `0.13.4` and have the PHP version change between the different packages


## Non composer installations

If you have a non-composer TYPO3 installation, don't worry.
Install TYPO3 Rector either as a global dependency:

```bash
composer global require --dev ssch/typo3-rector
```

Add an extra autoload file. In the example case it is placed in the Document Root of your TYPO3 project.
The autoload.php should look something like that for TYPO3 Version 9:

```php
<?php
use TYPO3\CMS\Core\Core\Bootstrap;
define('PATH_site', __DIR__.'/');
$classLoader = require PATH_site .'/typo3_src/vendor/autoload.php';

Bootstrap::getInstance()
         ->initializeClassLoader($classLoader)
         ->setRequestType(TYPO3_REQUESTTYPE_CLI)
         ->baseSetup();
```

For TYPO3 version 10 the autoload.php should look like this:

```php
<?php
use TYPO3\CMS\Core\Cache\Backend\NullBackend;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Configuration\ConfigurationManager;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Core\ClassLoadingInformation;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Package\UnitTestPackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

define('PATH_site', rtrim(strtr(getcwd(), '\\', '/'), '/').'/');
define('PATH_thisScript', PATH_site.'typo3/index.php');
$_SERVER['SCRIPT_NAME'] = PATH_thisScript;
putenv('TYPO3_PATH_ROOT='.getcwd());
define('TYPO3_MODE', 'BE');
define('TYPO3_PATH_PACKAGES', __DIR__.'/typo3_src/vendor/');

$classLoaderFilepath = TYPO3_PATH_PACKAGES.'autoload.php';

$classLoader = require $classLoaderFilepath;

$requestType = SystemEnvironmentBuilder::REQUESTTYPE_BE | SystemEnvironmentBuilder::REQUESTTYPE_CLI;
SystemEnvironmentBuilder::run(0, $requestType);

Bootstrap::initializeClassLoader($classLoader);
Bootstrap::baseSetup();

// Initialize default TYPO3_CONF_VARS
$configurationManager = new ConfigurationManager();
$GLOBALS['TYPO3_CONF_VARS'] = $configurationManager->getDefaultConfiguration();

$cache = new PhpFrontend('core', new NullBackend('production', []));
// Set all packages to active
$packageManager = Bootstrap::createPackageManager(UnitTestPackageManager::class, $cache);

GeneralUtility::setSingletonInstance(PackageManager::class, $packageManager);
ExtensionManagementUtility::setPackageManager($packageManager);

ClassLoadingInformation::dumpClassLoadingInformation();
ClassLoadingInformation::registerClassLoadingInformation();
```
For TYPO3 version 11 the autoload.php should look like this:

```php
<?php
use TYPO3\CMS\Core\Cache\Backend\NullBackend;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Configuration\ConfigurationManager;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Core\ClassLoadingInformation;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Package\UnitTestPackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Package\Cache\PackageStatesPackageCache;
use TYPO3\CMS\Core\Core\Environment;

define('PATH_site', rtrim(strtr(getcwd(), '\\', '/'), '/').'/');
define('PATH_thisScript', PATH_site.'typo3/index.php');
$_SERVER['SCRIPT_NAME'] = PATH_thisScript;
putenv('TYPO3_PATH_ROOT='.getcwd());
define('TYPO3_MODE', 'BE');
define('TYPO3_PATH_PACKAGES', __DIR__.'/typo3_src/vendor/');

$classLoaderFilepath = TYPO3_PATH_PACKAGES.'autoload.php';

$classLoader = require $classLoaderFilepath;

$requestType = SystemEnvironmentBuilder::REQUESTTYPE_BE | SystemEnvironmentBuilder::REQUESTTYPE_CLI;
SystemEnvironmentBuilder::run(0, $requestType);

Bootstrap::initializeClassLoader($classLoader);
Bootstrap::baseSetup();

// Initialize default TYPO3_CONF_VARS
$configurationManager = new ConfigurationManager();
$GLOBALS['TYPO3_CONF_VARS'] = $configurationManager->getDefaultConfiguration();

$cache = new PhpFrontend('core', new NullBackend('production', []));
$packageStateCache = new PackageStatesPackageCache(Environment::getLegacyConfigPath() . '/PackageStates.php', $cache);
// Set all packages to active
$packageManager = Bootstrap::createPackageManager(UnitTestPackageManager::class, $packageStateCache);

GeneralUtility::setSingletonInstance(PackageManager::class, $packageManager);
ExtensionManagementUtility::setPackageManager($packageManager);

ClassLoadingInformation::dumpClassLoadingInformation();
ClassLoadingInformation::registerClassLoadingInformation();
```
Afterwards run rector:

```bash
php ~/.composer/vendor/bin/rector process -c .rector/config.php --autoload-file=autoload.php
```

Note that the path to the rector executable can vary on your system.
