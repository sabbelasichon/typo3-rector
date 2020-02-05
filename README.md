## Disclaimer

!!! This repository is still under active development. No stable version released yet. !!!

# Rector for TYPO3

Apply automatic fixes on your TYPO3 code.

[![Coverage Status](https://img.shields.io/coveralls/sabbelasichon/typo3-rector/master.svg?style=flat-square)](https://coveralls.io/github/sabbelasichon/typo3-rector?branch=master)

[Rector](https://getrector.org/) aims to provide instant upgrades and instant refactoring of any PHP 5.3+ code. There are already [444 common recotors](https://github.com/rectorphp/rector/blob/master/docs/AllRectorsOverview.md) available. This project adds rectors specific to TYPO3 to help you migrate between TYPO3 releases.

## Installation

Install the library.

```bash
$ composer require --dev ssch/typo3-rector
```

## What Can Rector Do for You?

...**look at the overview of [all available TYPO3 Rectors](/docs/AllRectorsOverview.md)** with before/after diffs and configuration examples.

## Contributing

Want to help? Great!

### Fork the project

Fork this project into your own account.

### Install typo3-rector

Install the project using composer:
```bash
git clone https://github.com/your-account/typo3-rector.git
cd typo3-rector
composer install
```

### Pick an issue from the list

https://github.com/sabbelasichon/typo3-rector/issues You can filter by tags

### Assign the issue to yourself

Assign the issue to yourself so others can see that you are working on it.

### Create Rector

1. Find a place to store the Rector in `src/Rector`. What is the most logical folder structure?
2. Create a stub class in `stubs` if needed.
3. Write your rector.
4. Make sure your new Rector class is autoloaded: `composer du`
5. Register your rector in one of the yaml files in the `config` directory
6. Write a test for your rector.

### All tests must be green
Make sure you have a test in place for your Rector

All unit tests must pass before submitting a pull request.

```bash
./bin/phpunit
```

### Submit your changes

Great, now you can submit your changes in a pull request

### Non composer installations ###

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
php ~/.composer/vendor/bin/rector process public/typo3conf/ext/your_extension/  -c .rector/config.yaml -n --autoload-file autoload.php
```
