<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions;

use Nette\Utils\Strings;
use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\Conditions\TyposcriptConditionMatcher;

final class BrowserConditionMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var string
     */
    private const TYPE = 'browser';

    public function change(string $condition): ?string
    {
        return null;
    }

    public function shouldApply(string $condition): bool
    {
        return Strings::startsWith($condition, self::TYPE);
    }
}
