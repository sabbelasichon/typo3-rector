<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions;

use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\Conditions\TyposcriptConditionMatcher;
use Ssch\TYPO3Rector\Helper\ArrayUtility;

final class UsergroupConditionMatcherMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var string
     */
    private const TYPE = 'usergroup';

    public function change(string $condition): ?string
    {
        preg_match('#' . self::TYPE . '\s*=\s*(.*)#', $condition, $matches);

        if (! is_array($matches)) {
            return $condition;
        }

        if (! isset($matches[1]) || $matches[1] === '') {
            return "usergroup('*') == false";
        }

        $values = ArrayUtility::trimExplode(',', $matches[1], true);

        return sprintf('usergroup("%s")', implode(',', $values));
    }

    public function shouldApply(string $condition): bool
    {
        return preg_match('#^' . self::TYPE . self::ZERO_ONE_OR_MORE_WHITESPACES . '=[^=]#', $condition) === 1;
    }
}
