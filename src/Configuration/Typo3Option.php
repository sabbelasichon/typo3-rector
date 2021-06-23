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
    public const BOOTSTRAP_FILE = __DIR__ . '/../../config/bootstrap.php';
}
