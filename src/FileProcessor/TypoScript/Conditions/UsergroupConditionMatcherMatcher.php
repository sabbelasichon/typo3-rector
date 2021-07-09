<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions;

use Nette\Utils\Strings;
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
        $matches = Strings::match($condition, '#' . self::TYPE . '\s*=\s*(.*)#');

        if (! is_array($matches)) {
            return $condition;
        }
        if (! isset($matches[1])) {
            return "usergroup('*') == false";
        }
        if ('' === $matches[1]) {
            return "usergroup('*') == false";
        }

        $values = ArrayUtility::trimExplode(',', $matches[1], true);

        return sprintf('usergroup("%s")', implode(',', $values));
    }

    public function shouldApply(string $condition): bool
    {
        return (bool) Strings::match($condition, '#^' . self::TYPE . self::ZERO_ONE_OR_MORE_WHITESPACES . '=[^=]#');
    }
}
