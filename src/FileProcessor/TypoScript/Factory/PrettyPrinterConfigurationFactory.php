<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Factory;

use Helmich\TypoScriptParser\Parser\Printer\PrettyPrinterConfiguration;
use Rector\Core\Configuration\Parameter\ParameterProvider;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\ValueObject\Indent;

final class PrettyPrinterConfigurationFactory
{
    /**
     * @readonly
     */
    private ParameterProvider $parameterProvider;

    public function __construct(ParameterProvider $parameterProvider)
    {
        $this->parameterProvider = $parameterProvider;
    }

    public function createPrettyPrinterConfiguration(File $file): PrettyPrinterConfiguration
    {
        // keep original TypoScript format
        $indent = Indent::fromFile($file);

        $prettyPrinterConfiguration = PrettyPrinterConfiguration::create();

        if ($indent->isSpace()) {
            // default indent
            $indentation = $this->parameterProvider->provideParameter(
                Typo3Option::TYPOSCRIPT_INDENT_SIZE
            ) ?? $indent->length();
            $prettyPrinterConfiguration = $prettyPrinterConfiguration->withSpaceIndentation($indentation);
        } else {
            $prettyPrinterConfiguration = $prettyPrinterConfiguration->withTabs();
        }

        if ($this->parameterProvider->provideParameter(Typo3Option::TYPOSCRIPT_INDENT_CONDITIONS) ?? false) {
            $prettyPrinterConfiguration = $prettyPrinterConfiguration->withIndentConditions();
        }

        if ($this->parameterProvider->provideParameter(Typo3Option::TYPOSCRIPT_WITH_CLOSING_GLOBAL_STATEMENT) ?? true) {
            $prettyPrinterConfiguration = $prettyPrinterConfiguration->withClosingGlobalStatement();
        }

        if ($this->parameterProvider->provideParameter(Typo3Option::TYPOSCRIPT_WITH_EMPTY_LINE_BREAKS) ?? true) {
            $prettyPrinterConfiguration = $prettyPrinterConfiguration->withEmptyLineBreaks();
        }
        return $prettyPrinterConfiguration;
    }
}
