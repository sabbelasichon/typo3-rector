## Disclaimer

!!! This repository is still under active development. No stable version released yet. !!!

# Rector for TYPO3

Apply automatic fixes on your TYPO3 code.

[![Coverage Status](https://img.shields.io/coveralls/sabbelasichon/typo3-rector/master.svg?style=flat-square)](https://coveralls.io/github/sabbelasichon/typo3-rector?branch=master)

[Rector](https://getrector.org/) aims to provide instant upgrades and instant refactoring of any PHP 5.3+ code. This project adds rectors specific to TYPO3 to help you migrate between TYPO3 releases.

## Installation

Install the library.

```bash
$ composer require --dev ssch/typo3-rector
```

## What Can Rector Do for You?

...**look at the overview of [all available TYPO3 Rectors](/docs/all_rectors_overview.md)** with before/after diffs and configuration examples.

You can also watch the video from the T3CRR conference:

[![RectorPHP for TYPO3](https://img.youtube.com/vi/FeU3XEG0AW4/0.jpg)](https://www.youtube.com/watch?v=FeU3XEG0AW4)


## Configuration and Processing

This library ships already with a bunch of configuration files organized by TYPO3 version.
In order to "fix" your code with the desired rectors create your own configuration file in the PHP format:

```php
<?php

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/vendor/ssch/typo3-rector/config/typo3-90.php');
    $containerConfigurator->import(__DIR__ . '/vendor/ssch/typo3-rector/config/typo3-93.php');
    $containerConfigurator->import(__DIR__ . '/vendor/ssch/typo3-rector/config/typo3-94.php');
    $containerConfigurator->import(__DIR__ . '/vendor/ssch/typo3-rector/config/typo3-95.php');

    $parameters = $containerConfigurator->parameters();

    // FQN classes are not imported by default. If you don't to do do it manually after every Rector run, enable it by:
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    // this will not import root namespace classes, like \DateTime or \Exception
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

    // this will not import classes used in PHP DocBlocks, like in /** @var \Some\Class */
    $parameters->set(Option::IMPORT_DOC_BLOCKS, false);

    $parameters->set(Option::PHP_VERSION_FEATURES, '7.2');

    // If you set option Option::AUTO_IMPORT_NAMES to true, you should consider excluding some TYPO3 files.
    $parameters->set(Option::EXCLUDE_PATHS, [
        'ClassAliasMap.php',
        'class.ext_update.php',
        'ext_localconf.php',
        'ext_emconf.php',
        'ext_tables.php',
        __DIR__ . '/packages/my_package/Configuration/*'
    ]);

};
```

See [Rector README](https://github.com/rectorphp/rector#configuration) for full configuration options.

This configuration applies all available rectors defined in the different PHP files for version 9.x with PHP 7.2 capabilities.
Additionally we auto import the Full Qualified Class Names except for PHP core classes and within DocBlocks.

After the configuration run rector to simulate (hence the option -n) the code fixes:

```bash
./vendor/bin/rector process packages/my_custom_extension --config=my_config.php --dry-run
```

Check if everything makes sense and run the process command without the `--dry-run` option to apply changes.

## Contributing

Want to help? Great!
Joing TYPO3 slack channel #ext-typo3-rector

### Fork the project

Fork this project into your own account.

### Install typo3-rector

Install the project using composer:
```bash
git clone git@github.com:your-account/typo3-rector.git
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
5. Register your rector in one of the php files in the `config` directory
6. Write a test for your rector.

Or you can use `rector-recipe.php` [file to generate base structure for the rule](https://github.com/rectorphp/rector/blob/master/docs/rector_recipe.md).

### All Tests must be Green

Make sure you have a test in place for your Rector

All unit tests must pass before submitting a pull request.

```bash
./vendor/bin/phpunit
```

### Submit your changes

Great, now you can submit your changes in a pull request

### Non composer installations

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
php ~/.composer/vendor/bin/rector process public/typo3conf/ext/your_extension/  -c .rector/config.php -n --autoload-file autoload.php
```

### Composer conflics ###
It is not uncommon to run into unresolvable composer conflicts when installing typo3-rector, especially with older TYPO3 Versions (< 9.5 LTS), for example TYPO3 8.7 LTS. In this case, you have two options:

#### Solution #1 ####

Install typo3-rector as a global dependency. It should do the job just as with non-composer-installations (see above).

```bash
$ composer global require --dev ssch/typo3-rector
```

When running rector, make sure to explicitly point to your config file:
```bash
cd /path/to/your/project-root
php ~/.composer/vendor/bin/rector process typo3conf/ext/your_extension/ --config my_config.php
```

Also, in your config file, make sure to adjust paths in the 'import' statements relative to your config file. Example:

```php
<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/vendor/ssch/typo3-rector/config/typo3-87.php');
    $containerConfigurator->import(__DIR__ . '/vendor/ssch/typo3-rector/config/typo3-93.php');
    $containerConfigurator->import(__DIR__ . '/vendor/ssch/typo3-rector/config/typo3-94.php');
    $containerConfigurator->import(__DIR__ . '/vendor/ssch/typo3-rector/config/typo3-95.php');
};
```

As long as you place your config file in your project's root folder, creating or pointing to an autoloader should not be nescessary.

#### Solution #2

As an alternative to installing typo3-rector globally, you may also want to use the package [rector/rector-prefixed](https://github.com/rectorphp/rector-prefixed), which aims for maximum compatibility. Just install it via composer before installing typo3-rector, it works as a drop-in-replacement for rector/rector:

```bash
$ composer require --dev rector/rector-prefixed
$ composer require --dev ssch/typo3-rector
```


