<?php

declare(strict_types=1);

use Helmich\TypoScriptParser\Parser\AST\Builder;
use Helmich\TypoScriptParser\Parser\Parser;
use Helmich\TypoScriptParser\Parser\ParserInterface;
use Helmich\TypoScriptParser\Parser\Printer\ASTPrinterInterface;
use Helmich\TypoScriptParser\Parser\Printer\PrettyPrinter;
use Helmich\TypoScriptParser\Parser\Traverser\Traverser;
use Helmich\TypoScriptParser\Tokenizer\Tokenizer;
use Helmich\TypoScriptParser\Tokenizer\TokenizerInterface;
use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\TypoScriptFileProcessor;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->disableParallel();
    $rectorConfig->importNames();
    $rectorConfig->phpstanConfig(Typo3Option::PHPSTAN_FOR_RECTOR_PATH);
    // this will not import root namespace classes, like \DateTime or \Exception
    $rectorConfig->importShortClasses(false);

    $services = $rectorConfig->services();
    $services->defaults()
        ->public()
        ->autowire();
    $parameters = $rectorConfig->parameters();

    $services->set(Filesystem::class);

    $services->load('Ssch\\TYPO3Rector\\', __DIR__ . '/../src')
        ->exclude([
            __DIR__ . '/../src/Rector',
            __DIR__ . '/../src/AttributeDecorator/ExtbaseValidateAttributeDecorator.php',
            __DIR__ . '/../src/Console/Application/Typo3RectorKernel.php',
            __DIR__ . '/../src/FileProcessor/Composer/Rector',
            __DIR__ . '/../src/FileProcessor/FlexForms/Rector',
            __DIR__ . '/../src/FileProcessor/Fluid/Rector',
            __DIR__ . '/../src/FileProcessor/Resources/Files/Rector',
            __DIR__ . '/../src/FileProcessor/Resources/Icons/Rector',
            __DIR__ . '/../src/FileProcessor/TypoScript/Conditions',
            __DIR__ . '/../src/FileProcessor/TypoScript/PostRector',
            __DIR__ . '/../src/FileProcessor/TypoScript/Rector',
            __DIR__ . '/../src/FileProcessor/Yaml/Form/Rector',
            __DIR__ . '/../src/Set',
            __DIR__ . '/../src/ValueObject',
        ]);

    $services->set(Traverser::class);

    $services->set(Tokenizer::class);
    $services->alias(TokenizerInterface::class, Tokenizer::class);

    $services->set(PrettyPrinter::class);
    $services->alias(ASTPrinterInterface::class, PrettyPrinter::class);

    $services->set(Parser::class);
    $services->alias(ParserInterface::class, Parser::class);

    $services->set(BufferedOutput::class);
    $services->alias(OutputInterface::class, BufferedOutput::class);

    $services->set(Builder::class);

    $services->set(TypoScriptFileProcessor::class)
        ->call('configure', [[
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
        ]]);

    $services->set(\PhpParser\PrettyPrinter\Standard::class);
};
