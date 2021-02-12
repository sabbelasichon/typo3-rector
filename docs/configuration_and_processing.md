# Configuration and Processing

This library ships already with a bunch of configuration files organized by TYPO3 version.
To get you started quickly run the following command inside the root directory of your project:

```bash
./vendor/bin/typo3-rector typo3-init
```

The command generates a basic configuration skeleton which you can adapt to your needs.
The file is full of comments, so you can follow along what is going on.

Also have a look at the class [Typo3SetList](https://github.com/sabbelasichon/typo3-rector/blob/master/src/Set/Typo3SetList.php).
There you can find all the available sets you can configure in the configuration file.

To mitigate one of the most boring but also most tedious tasks, the TCA configuration, we offer dedicated sets for it.
Let´s say you want to migrate the TCA from a TYPO3 7 project to a TYPO3 9 project add the following sets to your configuration file:

```php
<?php

// rector.php
declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\PostRector\Rector\NameImportingPostRector;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\Set\Typo3SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::SETS, [
       Typo3SetList::TCA_76,
       Typo3SetList::TCA_87,
       Typo3SetList::TCA_95,
    ]);

    // FQN classes are not imported by default. If you don't do it manually after every Rector run, enable it by:
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    // this will not import root namespace classes, like \DateTime or \Exception
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

    // this will not import classes used in PHP DocBlocks, like in /** @var \Some\Class */
    $parameters->set(Option::IMPORT_DOC_BLOCKS, false);

    // Define your target version which you want to support
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_72);

    // If you would like to see the changelog url when a rector is applied
    $parameters->set(Typo3Option::OUTPUT_CHANGELOG, true);

    // If you set option Typo3Option::AUTO_IMPORT_NAMES to true, you should consider excluding some TYPO3 files.
    $parameters->set(Option::SKIP, [
        NameImportingPostRector::class => [
            'ClassAliasMap.php',
            'ext_localconf.php',
            'ext_emconf.php',
            'ext_tables.php',
            __DIR__ . '/**/TCA/*',
            __DIR__ . '/**/Configuration/RequestMiddlewares.php',
            __DIR__ . '/**/Configuration/Commands.php',
            __DIR__ . '/**/Configuration/AjaxRoutes.php',
            __DIR__ . '/**/Configuration/Extbase/Persistence/Classes.php',
        ],
    ]);

    // If you have trouble that rector cannot run because some TYPO3 constants are not defined add an additional constants file
    // Have a look at https://github.com/sabbelasichon/typo3-rector/blob/master/typo3.constants.php
    // $parameters->set(Option::AUTOLOAD_PATHS, [
    //    __DIR__ . '/typo3.constants.php'
    // ]);

    // get services (needed for register a single rule)
    // $services = $containerConfigurator->services();

    // register a single rule
    // $services->set(InjectAnnotationRector::class);
};
```

For more configuration options see [Rector README](https://github.com/rectorphp/rector#configuration).

After your adopt the configuration to your needs, run typo3-rector to simulate (hence the option `--dry-run`) the future code fixes:

```bash
./vendor/bin/typo3-rector process packages/my_custom_extension --dry-run
```

Check if everything makes sense and run the process command without the `--dry-run` option to apply the changes.
