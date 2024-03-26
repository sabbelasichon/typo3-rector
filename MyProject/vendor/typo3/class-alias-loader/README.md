Class Alias Loader [![Build Status](https://github.com/TYPO3/class-alias-loader/actions/workflows/tests.yml/badge.svg)](https://github.com/TYPO3/class-alias-loader/actions/workflows/tests.yml)
==================

## Introduction
The idea behind this composer package is, to provide backwards compatibility for libraries that want to rename classes
but still want to stay compatible for a certain amount of time with consumer packages of these libraries.

## What it does?
It provides an additional class loader which amends the composer class loader by rewriting the `vendor/autoload.php`
file when composer dumps the autoload information. This is only done if any of the packages that are installed by composer
provide a class alias map file, which is configured in the respective `composer.json`.

## How does it work?
If a package provides a mapping file which holds the mapping from old to new class name, the class loader registers itself
and transparently calls `class_alias()` for classes with an alias. If an old class name is requested, the original class
is loaded and the alias is established, so that third party packages can use old class names transparently.

## Configuration in composer.json

You can define multiple class alias map files in the extra section of the `composer.json` like this:

```
    "extra": {
        "typo3/class-alias-loader": {
            "class-alias-maps": [
                "Migrations/Code/ClassAliasMap.php"
            ]
        }
    },
```

Currently these files must be PHP files which return an associative array, where the keys are the old class names and the values the new class names.
Such a mapping file can look like this:

```
<?php
return array(
    'Tx_About_Controller_AboutController' => \TYPO3\CMS\About\Controller\AboutController::class,
    'Tx_About_Domain_Model_Extension' => \TYPO3\CMS\About\Domain\Model\Extension::class,
    'Tx_About_Domain_Repository_ExtensionRepository' => \TYPO3\CMS\About\Domain\Repository\ExtensionRepository::class,
    'Tx_Aboutmodules_Controller_ModulesController' => \TYPO3\CMS\Aboutmodules\Controller\ModulesController::class,
);
```

The '::class' constant is not available before PHP 5.5. Under a PHP before 5.5 the mapping file can look like this:

```
<?php
return array(
    'Tx_About_Controller_AboutController' => 'TYPO3\\CMS\\About\\Controller\\AboutController',
    'Tx_About_Domain_Model_Extension' => 'TYPO3\\CMS\\About\\Domain\\Model\\Extension',
    'Tx_About_Domain_Repository_ExtensionRepository' => 'TYPO3\\CMS\\About\\Domain\\Repository\\ExtensionRepository',
    'Tx_Aboutmodules_Controller_ModulesController' => 'TYPO3\\CMS\\Aboutmodules\\Controller\\ModulesController',
);
```

In your *root* `composer.json` file, you can decide whether to allow classes to be found that are requested with wrong casing.
Since PHP is case insensitive for class names, but PSR class loading standards bound file names to class names, class names de facto
become case sensitive. For legacy packages it may be useful however to allow class names to be loaded even if wrong casing is provided.
For this to work properly, you need to use the composer [optimize class loading information feature](https://getcomposer.org/doc/03-cli.md#global-options).


You can activate this feature like this:

```
    "extra": {
        "typo3/class-alias-loader": {
            "autoload-case-sensitivity": false
        }
    },
```

The default value of this option is `true`.

If no alias mapping is found and case sensitivity is set to `true` then by default this package does nothing. It means no additional class loading information is dumped
and the `vendor/autoload.php` is not changed. This enables library vendors to deliver compatibility packages which provide such aliases
for backwards compatibility, but keep the library clean (and faster) for new users.

In case you want your application to add alias maps during runtime, it may be useful however if the alias loader is always initialized.
Therefore it is possible to set the following option in your *root* `composer.json`:

```
    "extra": {
        "typo3/class-alias-loader": {
            "always-add-alias-loader": true
        }
    },
```


## Using the API

The public API is pretty simple and consists of only one class with only three static methods, `TYPO3\ClassAliasLoader\ClassAliasMap::getClassNameForAlias`
being the most important one.
You can use this class method if you have places in your application that deals with class names in strings and want to provide backwards compatibility there.
The API returns the original (new) class name if there is one, or the class name given if no alias is found.

The remaining methods, deal with adding alias maps during runtime, which generally is not recommended to do.

## Feedback appreciated

I'm happy for feedback, be it [feature requests](https://github.com/TYPO3/class-alias-loader/issues) or [bug reports](https://github.com/TYPO3/class-alias-loader/issues).

## Contribute

If you feel like contributing, please do a regular [pull request](https://github.com/TYPO3/class-alias-loader/pulls).
The package is pretty small. The only thing to respect is to follow [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) coding standard
and to add some tests for functionality you add or change.
