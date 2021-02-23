# Installation

Install the library.

```bash
$ composer require --dev ssch/typo3-rector
```

## Composer conflicts

It is not uncommon to run into unresolvable composer conflicts when installing typo3-rector, especially with older TYPO3 Versions (< 9.5 LTS), for example TYPO3 8.7 LTS.

The best solution is to install the package [ssch/typo3-rector-prefixed](https://github.com/sabbelasichon/typo3-rector-prefixed)

```bash
$ composer require ssch/typo3-rector-prefixed --dev
```

## Non composer installations

If you have a non composer TYPO3 installation. Don´t worry.
Install typo3-rector either as a global dependency:

```bash
$ composer global require --dev ssch/typo3-rector
```

Add an extra autoload file. In the example case it is placed in the Document Root of your TYPO3 project.
The autoload should look something like that:

```php
use TYPO3\CMS\Core\Core\Bootstrap;
define('PATH_site', __DIR__.'/');
$classLoader = require PATH_site .'/typo3_src/vendor/autoload.php';

Bootstrap::getInstance()
         ->initializeClassLoader($classLoader)
         ->setRequestType(TYPO3_REQUESTTYPE_CLI)
         ->baseSetup();
```

Afterwards run rector:

```bash
php ~/.composer/vendor/bin/typo3-rector process public/typo3conf/ext/your_extension/  -c .rector/config.php -n --autoload-file autoload.php
```
