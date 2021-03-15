<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript\Conditions;

use Nette\Utils\Strings;
use Ssch\TYPO3Rector\ArrayUtility;

final class ApplicationContextConditionMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var string
     */
    private const TYPE = 'applicationContext';

    public function change(string $condition): ?string
    {
        preg_match('#' . self::TYPE . '\s*=\s*(.*)#', $condition, $matches);

        if (! is_array($matches)) {
            return $condition;
        }

        $values = ArrayUtility::trimExplode(',', $matches[1], true);

        $newConditions = [];
        foreach ($values as $value) {
            if ($this->isRegularExpression($value)) {
                $newConditions[] = sprintf('applicationContext matches %s', $value);
            } else {
                $newConditions[] = sprintf('applicationContext == %s', $value);
            }
        }

        return implode(' || ', $newConditions);
    }

    public function shouldApply(string $condition): bool
    {
        return Strings::startsWith($condition, self::TYPE);
    }

    private function isRegularExpression(string $regularExpression): bool
    {
        return false !== @preg_match($regularExpression, '');
    }
}
