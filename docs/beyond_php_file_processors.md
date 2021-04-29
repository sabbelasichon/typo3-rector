# Beyond PHP Code - Entering the realm of FileProcessors

TYPO3 Rector and RectorPHP is all about PHP-Code. Well, yes and no.
Some time ago we introduced the concept of FileProcessors which can handle also non PHP files of your defined project paths.

In TYPO3 Rector specifically we have already five of them:

1. TypoScriptProcessor
1. FlexFormsProcessor
1. ExtensionComposerProcessor
1. IconsProcessor
1. FormYamlProcessor

## IconsProcessor
Let´s start with the simplest one the IconsProcessor:

The IconsProcessor takes the ext_icon.* in your extension directory and moves it under the Resources/Public/Icons/ directory with the name Extension.*

The IconsProcessor is part of the TYPO3_87 set.

## FlexFormsProcessor
The FlexFormsProcessor takes all xml files starting with the xml Node T3DataStructure and can do some modifications on it.
For now only the renderType is added in the config section if missing.

## ExtensionComposerProcessor
The ExtensionComposerProcessor takes all composer.json files of type typo3-cms-extension.
It adds an extension-key if it is missing. You can configure this Processor in your rector.php configuration file to add the typo3/cms-core dependency with the right version to your composer.json:

```php
# rector.php configuration file
use Ssch\TYPO3Rector\Rector\Composer\ExtensionComposerRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/config/config.php');

    $services = $containerConfigurator->services();

    $services->set(ExtensionComposerRector::class)->call('configure', [[
            ExtensionComposerRector::TYPO3_VERSION_CONSTRAINT => '^10.4'
    ]]);
};
```

## FormYamlProcessor
The FormYamlProcessor only transforms the old single key value pair of the EmailFinisher to an array syntax and is part of the TYPO3_104 set.

## TypoScriptProcessor
I think this is the most powerful Processor at the moment and can transform your old conditions to the Symfony Expression Language based ones.
It takes all of your TypoScript files ending of 'typoscript', 'ts', 'txt', 'pagets', 'constantsts', 'setupts', 'tsconfig', 't3s', 't3c', 'typoscriptconstants' and typoscriptsetupts into account.
This is also configurable in your rector.php configuration file:

```php
# rector.php configuration file
use Ssch\TYPO3Rector\TypoScript\TypoScriptProcessor;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/config/config.php');

    $services = $containerConfigurator->services();

    $services->set(TypoScriptProcessor::class)
        ->call('configure', [[
            TypoScriptProcessor::ALLOWED_FILE_EXTENSIONS => [
                'special',
            ],
        ]]);
};
```
