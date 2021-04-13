<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript\Conditions;

use Nette\Utils\Strings;
use Ssch\TYPO3Rector\ArrayUtility;

final class HostnameConditionMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var string
     */
    private const TYPE = 'hostname';

    public function change(string $condition): ?string
    {
        preg_match(
            '#' . self::TYPE
            . self::ZERO_ONE_OR_MORE_WHITESPACES . '='
            . self::ZERO_ONE_OR_MORE_WHITESPACES . '(.*)#',
            $condition, $matches);

        if (! is_array($matches)) {
            return $condition;
        }

        $values = ArrayUtility::trimExplode(',', $matches[1], true);

        $newConditions = [];
        foreach ($values as $value) {
            if (Strings::contains($value, '*')) {
                $newConditions[] = sprintf('like(request.getNormalizedParams().getHttpHost(), "%s")', $value);
            } else {
                $newConditions[] = sprintf('request.getNormalizedParams().getHttpHost() == "%s"', $value);
            }
        }

        return implode(' || ', $newConditions);
    }

    public function shouldApply(string $condition): bool
    {
        if (Strings::contains($condition, '{$')) {
            return false;
        }

        return 1 === preg_match('#^' . self::TYPE . self::ZERO_ONE_OR_MORE_WHITESPACES . '=[^=]#', $condition);
    }
}
