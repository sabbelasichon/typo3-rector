<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions;

use Nette\Utils\Strings;
use Ssch\TYPO3Rector\Contract\TypoScript\Conditions\TyposcriptConditionMatcher;

final class LanguageConditionMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var string
     */
    private const TYPE = 'language';

    public function change(string $condition): ?string
    {
        preg_match('#^' . self::TYPE . '\s*=\s*(?<value>.*)$#iUm', $condition, $matches);

        if (! is_string($matches['value'])) {
            return $condition;
        }

        return sprintf('siteLanguage("twoLetterIsoCode") == "%s"', trim($matches['value']));
    }

    public function shouldApply(string $condition): bool
    {
        return Strings::startsWith($condition, self::TYPE);
    }
}
