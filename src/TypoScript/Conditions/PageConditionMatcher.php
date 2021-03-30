<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript\Conditions;

use Nette\Utils\Strings;

final class PageConditionMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var string
     */
    private const TYPE = 'page';

    public function change(string $condition): ?string
    {
        preg_match('#' . self::TYPE . '\s*\|(.*)\s*=\s*(.*)#', $condition, $matches);

        if (! is_array($matches)) {
            return $condition;
        }

        if (! isset($matches[1])) {
            return $condition;
        }

        if (! isset($matches[2])) {
            return $condition;
        }

        return sprintf('page["%s"] == "%s"', trim($matches[1]), trim($matches[2]));
    }

    public function shouldApply(string $condition): bool
    {
        return Strings::startsWith($condition, self::TYPE);
    }
}
