<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript\Conditions;

use Nette\Utils\Strings;
use Ssch\TYPO3Rector\ArrayUtility;

abstract class AbstractRootlineConditionMatcher implements TyposcriptConditionMatcher
{
    public function change(string $condition): ?string
    {
        preg_match('#' . $this->getType() . '\s*=\s*(.*)#', $condition, $matches);

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
        return Strings::startsWith($condition, $this->getType());
    }

    abstract protected function getType(): string;

    abstract protected function getExpression(): string;
}
