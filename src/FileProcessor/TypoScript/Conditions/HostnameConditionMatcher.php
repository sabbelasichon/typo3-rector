<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions;

use Nette\Utils\Strings;
use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\Conditions\TyposcriptConditionMatcher;
use Ssch\TYPO3Rector\Helper\ArrayUtility;

final class HostnameConditionMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var string
     */
    private const TYPE = 'hostname';

    public function change(string $condition): ?string
    {
        $matches = Strings::match($condition, '#' . self::TYPE
        . self::ZERO_ONE_OR_MORE_WHITESPACES . '='
        . self::ZERO_ONE_OR_MORE_WHITESPACES . '(.*)#');

        if (! is_array($matches)) {
            return $condition;
        }

        $values = ArrayUtility::trimExplode(',', $matches[1], true);

        $newConditions = [];
        foreach ($values as $value) {
            if (\str_contains($value, '*')) {
                $newConditions[] = sprintf('like(request.getNormalizedParams().getHttpHost(), "%s")', $value);
            } else {
                $newConditions[] = sprintf('request.getNormalizedParams().getHttpHost() == "%s"', $value);
            }
        }

        return implode(' || ', $newConditions);
    }

    public function shouldApply(string $condition): bool
    {
        if (\str_contains($condition, self::CONTAINS_CONSTANT)) {
            return false;
        }

        return (bool) Strings::match($condition, '#^' . self::TYPE . self::ZERO_ONE_OR_MORE_WHITESPACES . '=[^=]#');
    }
}
