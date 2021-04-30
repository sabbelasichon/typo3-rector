<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\EditorConfig;

use Idiosyncratic\EditorConfig\Declaration\EndOfLine;
use Idiosyncratic\EditorConfig\Declaration\IndentSize;
use Idiosyncratic\EditorConfig\Declaration\IndentStyle;
use Idiosyncratic\EditorConfig\EditorConfig;
use Ssch\TYPO3Rector\ValueObject\EditorConfigConfiguration;
use Symplify\SmartFileSystem\SmartFileInfo;

final class EditorConfigIdiosyncraticParser implements EditorConfigParser
{
    /**
     * @var string
     */
    private const INDENT_STYLE = 'indent_style';

    /**
     * @var string
     */
    private const INDENT_SIZE = 'indent_size';

    /**
     * @var string
     */
    private const END_OF_LINE = 'end_of_line';

    /**
     * @var EditorConfig
     */
    private $editorConfig;

    public function __construct(EditorConfig $editorConfig)
    {
        $this->editorConfig = $editorConfig;
    }

    public function extractConfigurationForFile(
        SmartFileInfo $smartFileInfo,
        EditorConfigConfiguration $defaultEditorConfiguration
    ): EditorConfigConfiguration {
        $configuration = $this->editorConfig->getConfigForPath($smartFileInfo->getRealPath());

        $identStyle = $defaultEditorConfiguration->getIndentStyle();
        $identSize = $defaultEditorConfiguration->getIndentSize();
        $endOfLine = $defaultEditorConfiguration->getEndOfLine();

        if (array_key_exists(
            self::INDENT_STYLE,
            $configuration
        ) && $configuration[self::INDENT_STYLE] instanceof IndentStyle) {
            $identStyle = $configuration[self::INDENT_STYLE]->getValue();
        }

        if (array_key_exists(
            self::INDENT_SIZE,
            $configuration
        ) && $configuration[self::INDENT_SIZE] instanceof IndentSize) {
            $identSize = $configuration[self::INDENT_SIZE]->getValue();
        }

        if (array_key_exists(
                self::END_OF_LINE,
                $configuration
            ) && $configuration[self::END_OF_LINE] instanceof EndOfLine) {
            $endOfLine = $configuration[self::END_OF_LINE]->getValue();
        }

        return new EditorConfigConfiguration($identStyle, $identSize, $endOfLine);
    }
}
