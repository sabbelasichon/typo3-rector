# Installation

Install the library.

```bash
$ composer require --dev ssch/typo3-rector
```

## Composer conflicts ##

It is not uncommon to run into unresolvable composer conflicts when installing typo3-rector, especially with older TYPO3 Versions (< 9.5 LTS), for example TYPO3 8.7 LTS. In this case, you have multiple options:

### Solution #1 ###

The best solution is to install the package [ssch/typo3-rector-shim](https://github.com/sabbelasichon/typo3-rector-shim)

```bash
$ composer require --dev ssch/typo3-rector-shim
```

### Solution #2 ###

As an alternative to installing ssch/typo3-rector-shim, you may also want to use the package [rector/rector-prefixed](https://github.com/rectorphp/rector-prefixed), which aims for maximum compatibility. Just install it via composer before installing ssch/typo3-rector, it works as a drop-in-replacement for rector/rector:

```bash
$ composer require --dev rector/rector-prefixed
$ composer require --dev ssch/typo3-rector
```

## Non composer installations

If you have a non composer TYPO3 installation. Don´t worry.
Install typo3-rector as a global dependency:

```bash
$ composer global require --dev ssch/typo3-rector
```

Add an extra autoload file. In the example case it is placed in the Document Root of your TYPO3 project.
The autoload could look something like that:

```php
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Core\ClassLoadingInformation;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;

define('PATH_site', __DIR__.'/public/');
$classLoader = require PATH_site.'/typo3_src/vendor/autoload.php';

SystemEnvironmentBuilder::run(0, SystemEnvironmentBuilder::REQUESTTYPE_CLI);

ClassLoadingInformation::setClassLoader($classLoader);
if (ClassLoadingInformation::isClassLoadingInformationAvailable()) {
    ClassLoadingInformation::registerClassLoadingInformation();
}

Bootstrap::initializeClassLoader($classLoader);
```

Afterwards run rector:

```bash
php ~/.composer/vendor/bin/typo3-rector process public/typo3conf/ext/your_extension/  -c .rector/config.php -n --autoload-file autoload.php
```
