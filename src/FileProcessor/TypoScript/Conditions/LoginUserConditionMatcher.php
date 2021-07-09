<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions;

use Nette\Utils\Strings;
use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\Conditions\TyposcriptConditionMatcher;
use Ssch\TYPO3Rector\Helper\ArrayUtility;

final class LoginUserConditionMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var string
     */
    private const TYPE = 'loginUser';

    public function change(string $condition): ?string
    {
        $matches = Strings::match($condition, '#' . self::TYPE . '\s*=\s*(.*)#');

        if (! is_array($matches)) {
            return $condition;
        }
        if (! isset($matches[1])) {
            return 'loginUser("*") == false';
        }
        if ('' === $matches[1]) {
            return 'loginUser("*") == false';
        }

        $values = ArrayUtility::trimExplode(',', $matches[1], true);

        return sprintf('loginUser("%s")', implode(',', $values));
    }

    public function shouldApply(string $condition): bool
    {
        return (bool) Strings::match($condition, '#^' . self::TYPE . self::ZERO_ONE_OR_MORE_WHITESPACES . '=#');
    }
}
