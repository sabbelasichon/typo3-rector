<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript\Conditions;

use Nette\Utils\Strings;

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
