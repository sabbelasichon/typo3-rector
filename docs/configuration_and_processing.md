## Table of Contents
1. [Examples in action](./examples_in_action.md)
1. [Overview of all rules](./all_rectors_overview.md)
1. [Installation](./installation.md)
1. [Configuration and Processing](./configuration_and_processing.md)
1. [Best practice guide](./best_practice_guide.md)
1. [Limitations](./limitations.md)
1. [Contribution](./contribution.md)

# Configuration and Processing

This library ships already with a bunch of configuration files organized by TYPO3 version.
To get started quickly, run the following command inside the **root directory** of your project:

```bash
vendor/bin/typo3-init
```

The command generates a basic configuration skeleton which you can adapt to your needs.

Also have a look at the class [Typo3SetList](https://github.com/sabbelasichon/typo3-rector/blob/main/src/Set/Typo3SetList.php).
There you can find all the available TYPO3 sets you can configure in the configuration file.

For more configuration options, see [Rector README](https://github.com/rectorphp/rector#configuration).

After you adopt the configuration to your needs, run `vendor/bin/rector --dry-run` to simulate (hence the option `--dry-run`) the future code fixes:

```bash
vendor/bin/rector process --dry-run
```

Check if everything makes sense and run the process command without the `--dry-run` option to apply the changes.

If you see some code change that you want to exclude, you can either exclude a single file to be skipped or you can also
exclude a single rule which you don't want to be applied.

Both can be done via the `skip` configuration option like so:

```php
return RectorConfig::configure()
    ->withSets([
        Typo3LevelSetList::UP_TO_TYPO3_12,
    ])
    ->withPaths([
        __DIR__ . '/packages',
    ])
    ->withSkip([
        RuleToBeSkippedRector::class,
        __DIR__ . '/packages/my_extension/Classes/FileToBeSkipped.php',
    ]);
```

---

## Use with the --config option
If the Rector configuration is not in the main directory (e.g. `/var/www/html/`), the CLI option --config must be added.
If the CLI option `--config` is used, the paths in the Rector configuration file must be adapted, as this is based on the path of the rector.php file in the standard configuration.

Instead of `__DIR__` the PHP method `getcwd()` must be used. This takes the starting point for the execution of Rector.

### Example with the option --config and custom rector.php location

The file `rector.php` is located in the directory` /var/www/Build/rector/` and it is executed
via` cd /var/www/html/ && ./vendor/bin/rector process --config ../Build/rector/rector.php`.
The starting point with the PHP method `getcwd()` is then `/var/www/html/` instead of `/var/www/html/Build/rector/` with `__DIR__`.

```php
$rectorConfig->skip([
    NameImportingPostRector::class => [
        'ClassAliasMap.php',
        'ext_localconf.php',
        'ext_emconf.php',
        'ext_tables.php',
        getcwd() . '/**/Configuration/TCA/*',
        getcwd() . '/**/Configuration/RequestMiddlewares.php',
        getcwd() . '/**/Configuration/Commands.php',
        getcwd() . '/**/Configuration/AjaxRoutes.php',
        getcwd() . '/**/Configuration/Extbase/Persistence/Classes.php'
    ]
])
```

### Example with the option --config and predefined paths in a custom rector.php location

In order to process the source files of only one TYPO3 extension, it's recommended to define said extension's path via the `Option::PATHS` parameter within the config file:

```php
// paths to refactor; solid alternative to CLI arguments
$rectorConfig->paths([
    getcwd() . '/**/acme_demo/'
]);
```
