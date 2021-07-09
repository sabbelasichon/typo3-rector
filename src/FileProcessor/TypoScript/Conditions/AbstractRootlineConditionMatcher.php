<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions;

use Nette\Utils\Strings;
use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\Conditions\TyposcriptConditionMatcher;
use Ssch\TYPO3Rector\Helper\ArrayUtility;

abstract class AbstractRootlineConditionMatcher implements TyposcriptConditionMatcher
{
    public function change(string $condition): ?string
    {
        $matches = Strings::match($condition, '#' . $this->getType()
        . self::ZERO_ONE_OR_MORE_WHITESPACES . '='
        . self::ZERO_ONE_OR_MORE_WHITESPACES . '(.*)#');

        if (! is_array($matches)) {
            return $condition;
        }

        $values = ArrayUtility::trimExplode(',', $matches[1], true);

        $newConditions = [];
        foreach ($values as $value) {
            $newConditions[] = sprintf('%s in %s', $value, $this->getExpression());
        }

        return implode(' || ', $newConditions);
    }

    public function shouldApply(string $condition): bool
    {
        if (\str_contains($condition, self::CONTAINS_CONSTANT)) {
            return false;
        }

        return \str_starts_with($condition, $this->getType());
    }

    abstract protected function getType(): string;

    abstract protected function getExpression(): string;
}
