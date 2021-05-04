<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript\Conditions;

use Nette\Utils\Strings;
use Ssch\TYPO3Rector\Helper\ArrayUtility;

abstract class AbstractGlobalConditionMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var array
     */
    public const IENV_MAPPING = [
        'HTTP_HOST' => 'getHttpHost',
        'TYPO3_HOST_ONLY' => 'getRequestHostOnly',
        'TYPO3_SSL' => 'isHttps',
    ];

    protected function refactorTsfe(string $property, string $operator, string $value): string
    {
        if (Strings::startsWith($property, 'page')) {
            $parameters = ArrayUtility::trimExplode('|', $property, true);
            return sprintf('page["%s"] %s %s', $parameters[1], self::OPERATOR_MAPPING[$operator], $value);
        }

        return sprintf('getTSFE().%s %s %s', $property, self::OPERATOR_MAPPING[$operator], $value);
    }

    protected function createEnvCondition(string $property, string $operator, string $value): string
    {
        return sprintf('getenv("%s") %s "%s"', $property, self::OPERATOR_MAPPING[$operator], $value);
    }

    protected function createIndependentCondition(string $property, string $operator, string $value): string
    {
        if (Strings::contains($value, '*')) {
            return sprintf('like(request.getNormalizedParams().%s(), "%s")', self::IENV_MAPPING[$property], $value);
        }

        return sprintf(
            'request.getNormalizedParams().%s() %s "%s"',
            self::IENV_MAPPING[$property],
            self::OPERATOR_MAPPING[$operator],
            $value
        );
    }
}
