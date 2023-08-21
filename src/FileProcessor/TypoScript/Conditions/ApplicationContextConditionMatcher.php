<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions;

use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\Conditions\TyposcriptConditionMatcher;
use Ssch\TYPO3Rector\Helper\ArrayUtility;

final class ApplicationContextConditionMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var string
     */
    private const TYPE = 'applicationContext';

    public function change(string $condition): ?string
    {
        preg_match(
            '#' . self::TYPE
            . self::ZERO_ONE_OR_MORE_WHITESPACES . '='
            . self::ZERO_ONE_OR_MORE_WHITESPACES . '(.*)#',
            $condition,
            $matches
        );

        if (! is_array($matches)) {
            return $condition;
        }

        $values = ArrayUtility::trimExplode(',', $matches[1], true);

        $newConditions = [];
        foreach ($values as $value) {
            if ($this->isRegularExpression($value)) {
                $newConditions[] = sprintf('applicationContext matches "%s"', $value);
            } else {
                $newConditions[] = sprintf('applicationContext == "%s"', $value);
            }
        }

        return implode(' || ', $newConditions);
    }

    public function shouldApply(string $condition): bool
    {
        if (\str_contains($condition, self::CONTAINS_CONSTANT)) {
            return false;
        }

        return 1 === preg_match('#^' . self::TYPE . self::ZERO_ONE_OR_MORE_WHITESPACES . '=[^=]#', $condition);
    }

    private function isRegularExpression(string $regularExpression): bool
    {
        return false !== @preg_match($regularExpression, '');
    }
}
