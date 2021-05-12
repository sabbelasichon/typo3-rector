<?php

declare(strict_types=1);

use Helmich\TypoScriptParser\Parser\Parser;
use Helmich\TypoScriptParser\Parser\ParserInterface;
use Helmich\TypoScriptParser\Parser\Printer\ASTPrinterInterface;
use Helmich\TypoScriptParser\Parser\Printer\PrettyPrinter;
use Helmich\TypoScriptParser\Parser\Traverser\Traverser;
use Helmich\TypoScriptParser\Tokenizer\Tokenizer;
use Helmich\TypoScriptParser\Tokenizer\TokenizerInterface;
use Rector\Core\Configuration\Option;
use Ssch\TYPO3Rector\TypoScript\TypoScriptProcessor;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../utils/**/config/config.php', null, true);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, __DIR__ . '/../utils/phpstan/config/extension.neon');

    $services = $containerConfigurator->services();
    $services->defaults()
        ->public()
        ->autowire();

    $services->load('Ssch\\TYPO3Rector\\', __DIR__ . '/../src')
        ->exclude([
            __DIR__ . '/../src/Rector',
            __DIR__ . '/../src/Set',
            __DIR__ . '/../src/ValueObject',
            __DIR__ . '/../src/TypoScript/Conditions',
            __DIR__ . '/../src/TypoScript/Visitors',
            __DIR__ . '/../src/FlexForms/Rector',
            __DIR__ . '/../src/Yaml/Form/Rector',
            __DIR__ . '/../src/Resources/Icons/IconsProcessor.php',
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
