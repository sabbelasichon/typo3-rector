## Caution

Never run this tool on production, only on development environment where code is under version control (e.g. git). Always review and test automatic changes before releasing to production.

# Rector for TYPO3

Apply automatic fixes on your TYPO3 code.

[![Coverage Status](https://img.shields.io/coveralls/sabbelasichon/typo3-rector/master.svg?style=flat-square)](https://coveralls.io/github/sabbelasichon/typo3-rector?branch=master)

[Rector](https://getrector.org/) aims to provide instant upgrades and instant refactoring of any PHP 5.3+ code. This project adds rectors specific to TYPO3 to help you migrate between TYPO3 releases.

## Let´s see some examples in action
Let´s see some "rules" in action. Let´s say you have a Fluid ViewHelper looking like this:

```php
class InArrayViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Checks if given $uid is in a given $array
     *
     * @param int $uid the uid
     * @param array $arrayToCheckAgainst the array to check for the given $uid
     * @return bool
     */
    public function render($uid, array $arrayToCheckAgainst)
    {
        if (in_array($uid, $arrayToCheckAgainst)) {
           return true;
        } else {
           return false;
        }
    }
}
```

What´s "wrong" with this code? Well, it depends on the context. But, if we assume you would like to have this code ready for TYPO3 version 10 you should move the render method arguments to the method initializeArguments and you should rename the namespace \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper to \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper.

And we are not talking about the superfluous else statement and not having Type Declarations if we would like to use modern PHP. That´s a different story.

Do you like to do these changes manually on a codebase with let´s say 40-100 ViewHelpers? We don´t. So let Rector do the heavy work for us and apply the "rules" MoveRenderArgumentsToInitializeArgumentsMethodRector and RenameClassMapAliasRector for Version 9.5.

Rector transforms this code for us to the following one:
```php
class InArrayViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('uid', 'int', 'the uid', true);
        $this->registerArgument('arrayToCheckAgainst', 'array', 'the array to check for the given $uid', true);
    }

    /**
     * Checks if given $uid is in a given $array
     *
     * @return bool
     */
    public function render()
    {
        $uid = $this->arguments['uid'];
        $arrayToCheckAgainst = $this->arguments['arrayToCheckAgainst'];
        if (in_array($uid, $arrayToCheckAgainst)) {
           return true;
        } else {
           return false;
        }
    }
}
```
Isn´t this amazing? You don´t even have to know that these change has to be done. Your changelog resides in living code.

Let´s see another one:
```php
final class SomeService
{
    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\Annotation\Source\InjectionClass
     * @inject
     */
    protected $injectMe;
}
```
So we guess, everyone knows that TYPO3 switched to Doctrine Annotations on the one hand and you should better use either constructor injection or setter injection. Again, if you have only one class, this change is not a problem. But most of the time you have hundreds of them and you have to remember what to do. This is cumbersome and error prone. So let´s run Rector for us with the InjectAnnotationRector and you get this:
```php
use Ssch\TYPO3Rector\Tests\Rector\Annotation\Source\InjectionClass;

final class SomeInjectClass
{
    /**
     * @var \Ssch\TYPO3Rector\Tests\Rector\Annotation\Source\InjectionClass
     */
    protected $injectMe;

    public function injectInjectMe(InjectionClass $inject): void
    {
        $this->inject = $inject;
    }
}
```
Cool. Let me show you one more example.

Let´s say you want to upgrade vom version 9 to 10 and you have the following code:

```php
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Configuration\Exception\NoSuchOptionException;

class MyActionController extends ActionController
{
    public function exceptionAction()
    {
        $foo = 'foo';
        $bar = 'bar';
        if($foo !== $bar) {
            throw new NoSuchOptionException();
        }
    }
}
```

Can you spot the error? Guess not. At least i couldn´t.
The exception class NoSuchOptionException does not exist anymore in version 10. What. But it still worked in version 9. Why?
Because TYPO3 offers a nice way to deprecate such changes for one major version with these handy ClassAliasMap.php files.
But, postponed is not abandoned. You have to react to these changes at a certain time. Do you know all these changes by heart? Sure not.

So, again, let rector do it for you with the RenameClassMapAliasRector. Have a look at an example [config file](/config/v9/typo3-95.php#L44) shipped with typo3-rector

And there is more...

...**look at the overview of [all available TYPO3 Rectors](/docs/all_rectors_overview.md)** with before/after diffs and configuration examples.

You can also watch the video from the T3CRR conference:

[![RectorPHP for TYPO3](https://img.youtube.com/vi/FeU3XEG0AW4/0.jpg)](https://www.youtube.com/watch?v=FeU3XEG0AW4)

## Installation

Install the library.

```bash
$ composer require --dev ssch/typo3-rector
```

### Composer conflicts ###

It is not uncommon to run into unresolvable composer conflicts when installing typo3-rector, especially with older TYPO3 Versions (< 9.5 LTS), for example TYPO3 8.7 LTS. In this case, you have multiple options:

#### Solution #1 ####

The best solution is to install the package [ssch/typo3-rector-shim](https://github.com/sabbelasichon/typo3-rector-shim)

```bash
$ composer require --dev ssch/typo3-rector-shim
```

#### Solution #2 ####

As an alternative to installing ssch/typo3-rector-shim, you may also want to use the package [rector/rector-prefixed](https://github.com/rectorphp/rector-prefixed), which aims for maximum compatibility. Just install it via composer before installing ssch/typo3-rector, it works as a drop-in-replacement for rector/rector:

```bash
$ composer require --dev rector/rector-prefixed
$ composer require --dev ssch/typo3-rector
```

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
php ~/.composer/vendor/bin/typo3-rector process public/typo3conf/ext/your_extension/  -c .rector/config.php -n --autoload-file autoload.php
```

## Configuration and Processing

This library ships already with a bunch of configuration files organized by TYPO3 version. [show config](/config/)
Let´s say want to migrate from version 8.x to 9.x, you could import the config sets for v8 and v9.
So create your own configuration file called rector.php in the root of your project the following way:

```php
<?php

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Ssch\TYPO3Rector\Set\Typo3SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::SETS, [
            Typo3SetList::TYPO3_87,
            Typo3SetList::TYPO3_95,
        ]
    );

    // FQN classes are not imported by default. If you don't do it manually after every Rector run, enable it by:
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

This configuration applies all available rectors defined in the different PHP files for version 8.x to 9.x with PHP 7.2 capabilities.
Additionally we auto import the FQCN except for PHP core classes and within DocBlocks.

After the configuration run typo3-rector to simulate (hence the option -n) the code fixes:

```bash
./vendor/bin/typo3-rector process packages/my_custom_extension --dry-run
```

Check if everything makes sense and run the process command without the `--dry-run` option to apply the changes.

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

Run command and answer all questions properly
```bash
./bin/typo3-rector typo3-create
```

Afterwards you have to write your Rector and your tests for it.
If you need, you have to create so called stubs.
Stubs contain basically the skeleton of the classes you would like to refactor.
Have a look at the stubs folder.

Last but not least, register your file in the right config file under the config folder (Maybe not necessary anymore in the near future).

### All Tests must be Green

Make sure you have a test in place for your Rector

All unit tests must pass before submitting a pull request.

```bash
./vendor/bin/phpunit
```

### Submit your changes

Great, now you can submit your changes in a pull request


