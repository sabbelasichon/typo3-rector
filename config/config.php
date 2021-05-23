<?php

declare(strict_types=1);

use Helmich\TypoScriptParser\Parser\Parser;
use Helmich\TypoScriptParser\Parser\ParserInterface;
use Helmich\TypoScriptParser\Parser\Printer\ASTPrinterInterface;
use Helmich\TypoScriptParser\Parser\Printer\PrettyPrinter;
use Helmich\TypoScriptParser\Parser\Traverser\Traverser;
use Helmich\TypoScriptParser\Tokenizer\Tokenizer;
use Helmich\TypoScriptParser\Tokenizer\TokenizerInterface;
use Ssch\TYPO3Rector\FileProcessor\Composer\Rector\ExtensionComposerRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\TypoScriptProcessor;
use Ssch\TYPO3Rector\Rector\General\ConvertTypo3ConfVarsRector;
use Ssch\TYPO3Rector\Rector\General\ExtEmConfRector;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../utils/**/config/config.php', null, true);

    $services = $containerConfigurator->services();
    $services->defaults()
        ->public()
        ->autowire();

    $services->load('Ssch\\TYPO3Rector\\', __DIR__ . '/../src')
        ->exclude([
            __DIR__ . '/../src/Rector',
            __DIR__ . '/../src/Set',
            __DIR__ . '/../src/ValueObject',
            __DIR__ . '/../src/FileProcessor/TypoScript/Conditions',
            __DIR__ . '/../src/FileProcessor/TypoScript/Visitors',
            __DIR__ . '/../src/FileProcessor/Yaml/Form/Rector',
            __DIR__ . '/../src/FileProcessor/Composer/Rector',
            __DIR__ . '/../src/FileProcessor/FlexForms/Rector',
            __DIR__ . '/../src/FileProcessor/Resources/Icons/Rector',
            __DIR__ . '/../src/FileProcessor/Fluid/Rector',
        ]);

    // Add some general TYPO3 rules
    $services->set(ConvertTypo3ConfVarsRector::class);
    $services->set(ExtEmConfRector::class);
    $services->set(ExtensionComposerRector::class);

    $services->set(Traverser::class);

    $services->set(Tokenizer::class);
    $services->alias(TokenizerInterface::class, Tokenizer::class);

    $services->set(PrettyPrinter::class);
    $services->alias(ASTPrinterInterface::class, PrettyPrinter::class);

    $services->set(Parser::class);
    $services->alias(ParserInterface::class, Parser::class);

    $services->set(BufferedOutput::class);
    $services->alias(OutputInterface::class, BufferedOutput::class);

    $services->set(TypoScriptProcessor::class)
        ->call('configure', [[
            TypoScriptProcessor::ALLOWED_FILE_EXTENSIONS => [
                'typoscript',
                'ts',
                'txt',
                'pagets',
                'constantsts',
                'setupts',
                'tsconfig',
                't3s',
                't3c',
                'typoscriptconstants',
                'typoscriptsetupts',
            ],
        ]]);
};
