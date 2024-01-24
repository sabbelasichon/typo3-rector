<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Configuration;

final class Typo3Option
{
    /**
     * @var string
     */
    public const PHPSTAN_FOR_RECTOR_PATH = __DIR__ . '/../../utils/phpstan/config/extension.neon';

    /**
     * @var string
     */
    public const TYPOSCRIPT_INDENT_SIZE = 'typoscript-indent-size';

    /**
     * @var string
     */
    public const TYPOSCRIPT_INDENT_CONDITIONS = 'typoscript-indent-conditions';

    /**
     * @var string
     */
    public const TYPOSCRIPT_WITH_CLOSING_GLOBAL_STATEMENT = 'typoscript-with-closing-global-statement';

    /**
     * @var string
     */
    public const TYPOSCRIPT_WITH_EMPTY_LINE_BREAKS = 'typoscript-with-empty-line-breaks';
}
