<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions;

use Nette\Utils\Strings;
use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\Conditions\TyposcriptConditionMatcher;

final class CompatVersionConditionMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var string
     */
    private const TYPE = 'compatVersion';

    public function change(string $condition): ?string
    {
        preg_match(
            '#' . self::TYPE
            . self::ZERO_ONE_OR_MORE_WHITESPACES . '='
            . self::ZERO_ONE_OR_MORE_WHITESPACES . '(?<value>.*)$#iUm',
            $condition,
            $matches
        );

        if (! is_string($matches['value'])) {
            return $condition;
        }

        return sprintf('compatVersion("%s")', trim($matches['value']));
    }

    public function shouldApply(string $condition): bool
    {
        return Strings::startsWith($condition, self::TYPE);
    }
}
