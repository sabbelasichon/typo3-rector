<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions;

use Nette\Utils\Strings;
use Ssch\TYPO3Rector\Contract\FileProcessor\TypoScript\Conditions\TyposcriptConditionMatcher;

final class LanguageConditionMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var string
     */
    private const TYPE = 'language';

    public function change(string $condition): ?string
    {
        $matches = Strings::match($condition, '#^' . self::TYPE . '\s*=\s*(?<value>.*)$#iUm');

        if (! is_string($matches['value'])) {
            return $condition;
        }

        return sprintf('siteLanguage("twoLetterIsoCode") == "%s"', trim($matches['value']));
    }

    public function shouldApply(string $condition): bool
    {
        return \str_starts_with($condition, self::TYPE);
    }
}
